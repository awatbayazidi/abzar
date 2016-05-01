<?php namespace AwatBayazidi\Abzar\Extensions\Shop\Eloquent;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShopUserModel extends Model
{
    protected $itemModel = 'AwatBayazidi\Abzar\Extensions\Shop\Eloquent\ShopItemModel';
    protected $cartModel = 'AwatBayazidi\Abzar\Extensions\Shop\Eloquent\ShopCartModel';
    protected $orderModel;



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

    //----------------- relations ---------------------------

    public function cart()
    {
        return $this->hasOne($this->getCartModelName(), 'user_id');
    }

    public function items()
    {
        return $this->hasMany($this->getItemModelName(), 'user_id');
    }


    public function orders()
    {
        return $this->hasMany($this->getOrderModelName(), 'user_id');
    }


    public function getShopIdAttribute()
    {
        return is_array($this->getKeyName()) ? 0 : $this->getKey();
    }

}