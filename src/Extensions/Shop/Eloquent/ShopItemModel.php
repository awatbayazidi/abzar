<?php namespace AwatBayazidi\Abzar\Extensions\Shop\Eloquent;


use Illuminate\Database\Eloquent\Model as baseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ShopItemModel extends baseModel
{

    protected $table = 'items';
    protected $itemRouteName = '';
    protected $itemRouteParams = [];
    protected $userModel = 'App\User';
    protected $cartModel = 'AwatBayazidi\Abzar\Extensions\Shop\Eloquent\ShopCartModel';
    protected $orderModel ='';

    protected $fillable = ['user_id', 'cart_id', 'shop_id', 'sku', 'price', 'tax', 'shipping', 'currency', 'quantity', 'class', 'reference_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
       // $this->table = Config::get('shop.item_table');
    }

    public function getUserModelName()
    {
        return $this->userModel;
    }

    public function getCartModelName()
    {
        return $this->cartModel;
    }

    public function getOrderModelName()
    {
        return $this->orderModel;
    }
    //----------------- relations ---------------------------

    public function user()
    {
        return $this->belongsTo($this->getUserModelName(), 'user_id');
    }

    public function cart()
    {
        return $this->belongsTo($this->getCartModelName(), 'cart_id');
    }


    public function order()
    {
        return $this->belongsTo($this->getOrderModelName(), 'order_id');
    }

    //----------------- attributes ---------------------------

    public function getHasObjectAttribute()
    {
        return array_key_exists('class', $this->attributes) && !empty($this->attributes['class']);
    }

    public function getIsShoppableAttribute()
    {
        return true;
    }

    public function getObjectAttribute()
    {
        return $this->hasObject ? call_user_func($this->attributes['class'] . '::find', $this->attributes['reference_id']) : null;
    }

    /**
     * Returns item name.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if ($this->hasObject) return $this->object->displayName;
        return isset($this->itemName) ? $this->attributes[$this->itemName] : (array_key_exists('name', $this->attributes) ? $this->attributes['name'] : '');
    }

    /**
     * Returns item id.
     *
     * @return mixed
     */
    public function getShopIdAttribute()
    {
        return is_array($this->getKeyName()) ? 0 : $this->getKey();
    }

    /**
     * Returns item url.
     *
     * @return string
     */
    public function getShopUrlAttribute()
    {
        if ($this->hasObject) return $this->object->shopUrl;
        if (!property_exists($this, 'itemRouteName') && !property_exists($this, 'itemRouteParams')) return '#';
        $params = [];
        foreach (array_keys($this->attributes) as $attribute) {
            if (in_array($attribute, $this->itemRouteParams)) $params[$attribute] = $this->attributes[$attribute];
        }
        return empty($this->itemRouteName) ? '#' : \route($this->itemRouteName, $params);
    }

    /**
     * Returns price formatted for display.
     *
     * @return string
     */
    public function getDisplayPriceAttribute()
    {
        return Shop::format($this->attributes['price']);
    }

    /**
     * Scope class by a given sku.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query  Query.
     * @param mixed                                 $sku    SKU.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereSKU($query, $sku)
    {
        return $query->where('sku', $sku);
    }

    public function scopeFindBySKU($query, $sku)
    {
        return $query->whereSKU($sku)->first();
    }

    /**
     * Returns formatted tax for display.
     *
     * @return string
     */
    public function getDisplayTaxAttribute()
    {
        return Shop::format(array_key_exists('tax', $this->attributes) ? $this->attributes['tax'] : 0.00);
    }

    /**
     * Returns formatted tax for display.
     *
     * @return string
     */
    public function getDisplayShippingAttribute()
    {
        return Shop::format(array_key_exists('shipping', $this->attributes) ? $this->attributes['shipping'] : 0.00);
    }

    /**
     * Returns flag indicating if item was purchased by user.
     *
     * @return bool
     */
    public function getWasPurchasedAttribute()
    {
        if (Auth::guest()) return false;
        return Auth::user()
            ->orders()
            ->whereSKU($this->attributes['sku'])
            ->whereStatusIn(config('shop.order_status_purchase'))
            ->count() > 0;
    }

}