<?php namespace AwatBayazidi\Abzar\Extensions\Shop\Eloquent;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShopCartModel extends Model
{

    protected $table = 'cart';
    private $cartCalculations = null;
    protected $userModel ='App\User';
    protected $itemModel ='AwatBayazidi\Abzar\Extensions\Shop\Eloquent\ShopItemModel';
    protected $cartModel ='';

    protected $orderModel;

    protected $fillable = ['user_id'];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getUserModelName()
    {
        return $this->userModel;
    }

    public function getItemModelName()
    {
        return $this->itemModel;
    }

    public function getCartModelName()
    {
        return $this->cartModel;
    }

    public function getOrderModelName()
    {
        return $this->orderModel;
    }


    public static function boot()
    {
        parent::boot();

        static::deleting(function($user)  {
            if (!method_exists($user->getUserModelName(), 'bootSoftDeletingTrait')) {
                $user->items->sync([]);
            }
            return true;
        });
    }
    //----------------- relations ---------------------------

    public function user()
    {
        return $this->belongsTo($this->getUserModelName(), 'user_id');
    }

    public function items()
    {
        return $this->hasMany($this->getItemModelName(), 'cart_id');
    }

    //--------------------------------------------------------
    private function getItem($sku)
    {
        $className  = $this->getItemModelName();
        $item       = new $className();
       // $item->newQuery()->where('sku', $sku)->where('cart_id', $this->getKey())->first();
        return $item->where('sku', $sku)
            ->where('cart_id', $this->attributes['id'])
            ->first();
    }
    /**
     * Adds item to cart.
     *
     * @param mixed $item     Item to add, can be an Store Item, a Model with ShopItemTrait or an array.
     * @param int   $quantity Item quantity in cart.
     */
    public function add($item, $quantity = 1, $quantityReset = false)
    {
        if (!is_array($item) && !$item->isShoppable) return;
        // Get item
        $cartItem = $this->getItem(is_array($item) ? $item['sku'] : $item->sku);
        // Add new or sum quantity
        if (empty($cartItem)) {
            $reflection = null;
            if (is_object($item)) {
                $reflection = new \ReflectionClass($item);
            }
            $cartItem = call_user_func($this->getItemModelName() . '::create', [
                'user_id'       => $this->user->getKey(),
                'cart_id'       => $this->getKey(),
                'sku'           => is_array($item) ? $item['sku'] : $item->sku,
                'price'         => is_array($item) ? $item['price'] : $item->price,
                'tax'           => is_array($item) ? (array_key_exists('tax', $item) ? $item['tax'] : 0) : (isset($item->tax) && !empty($item->tax) ? $item->tax : 0),
                'shipping'      => is_array($item) ? (array_key_exists('shipping', $item) ? $item['shipping'] : 0) : (isset($item->shipping) && !empty($item->shipping) ? $item->shipping : 0),
                'currency'      =>  $this->currency_symbol,
                'quantity'      => $quantity,
                'class'         => is_array($item) ? null : $reflection->getName(),
                'reference_id'  => is_array($item) ? null : $item->getKey(),
            ]);
        } else {
            $cartItem->quantity = $quantityReset ? $quantity : $cartItem->quantity + $quantity;
            $cartItem->save();
        }
        $this->resetCalculations();
        return $this;
    }

    /**
     * Removes an item from the cart or decreases its quantity.
     * Returns flag indicating if removal was successful.
     *
     * @param mixed $item     Item to remove, can be an Store Item, a Model with ShopItemTrait or an array.
     * @param int   $quantity Item quantity to decrease. 0 if wanted item to be removed completly.
     *
     * @return bool
     */
    public function remove($item, $quantity = 0)
    {
        // Get item
        $cartItem = $this->getItem(is_array($item) ? $item['sku'] : $item->sku);
        // Remove or decrease quantity
        if (!empty($cartItem)) {
            if (!empty($quantity)) {
                $cartItem->quantity -= $quantity;
                $cartItem->save();
                if ($cartItem->quantity > 0) return true;
            }
            $cartItem->delete();
        }
        $this->resetCalculations();
        return $this;
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasItem($sku, $requireAll = false)
    {
        if (is_array($sku)) {
            foreach ($sku as $skuSingle) {
                $hasItem = $this->hasItem($skuSingle);

                if ($hasItem && !$requireAll) {
                    return true;
                } elseif (!$hasItem && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->items() as $item) {
                if ($item->sku == $sku) {
                    return true;
                }
            }
        }

        return false;
    }


    public function scopeWhereUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWhereCurrent($query)
    {
        if (Auth::guest()) return $query;
        return $query->whereUser(Auth::user()->getKey());
    }

    public function scopeCurrent($query)
    {
        if (Auth::guest()) return $query;
        $cart = $query->whereCurrent()->first();
        if (empty($cart)) {
            $cart = call_user_func( get_class($this).'::create', [
                'user_id' =>  Auth::user()->getKey()
            ]);
        }
        return $cart;
    }

    public function scopeFindByUser($query, $userId)
    {
        if (empty($userId)) return;
        $cart = $query->whereUser($userId)->first();
        if (empty($cart)) {
            $cart = call_user_func(get_class($this) . '::create', [
                'user_id' =>  $userId
            ]);
        }
        return $cart;
    }


    public function placeOrder($statusCode = null)
    {
        if (empty($statusCode)) $statusCode = Config::get('shop.order_status_placement');
        // Create order
        $order = call_user_func( $this->getOrderModelName() . '::create', [
            'user_id'       => $this->user_id,
            'statusCode'    => $statusCode
        ]);
        // Map cart items into order
        for ($i = count($this->items) - 1; $i >= 0; --$i) {
            // Attach to order
            $this->items[$i]->order_id  = $order->id;
            // Remove from cart
            $this->items[$i]->cart_id   = null;
            // Update
            $this->items[$i]->save();
        }
        $this->resetCalculations();
        return $order;
    }

    /**
     * Whipes put cart
     */
    public function clear()
    {
        DB::table(Config::get('shop.item_table'))
            ->where('cart_id', $this->attributes['id'])
            ->delete();
        $this->resetCalculations();
        return $this;
    }

}