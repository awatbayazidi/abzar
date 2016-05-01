<?php namespace AwatBayazidi\Abzar\Extensions\Shop\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


abstract class Model extends BaseModel
{

    protected $cartTable = 'cart';
    protected $orderTable;
    protected $itemTable  = 'items';
    protected $cache_calculations  = true;
    protected $cache_calculations_minutes  = 15;
    protected $symbol  = '';
    protected $currency_symbol  = '';
    protected $display_price_format  = ':symbol:price';
    /**
     * Property used to stored calculations.
     * @var array
     */
    private $shopCalculations = null;

    public function format($value)
    {
        return preg_replace(
            ['/:symbol/', '/:price/', '/:currency/'],
            [$this->symbol, $value, $this->currency_symbol],
            $this->display_price_format
        );
    }

    public function getItemTableName()
    {
        return $this->itemTable;
    }

    public function getOrderTableName()
    {
        return $this->orderTable;
    }
    /**
     * Returns total amount of items in cart.
     *
     * @return int
     */
    public function getCountAttribute()
    {
        if (empty($this->shopCalculations)) $this->runCalculations();
        return round($this->shopCalculations->itemCount, 2);
    }

    /**
     * Returns total price of all the items in cart.
     *
     * @return float
     */
    public function getTotalPriceAttribute()
    {
        if (empty($this->shopCalculations)) $this->runCalculations();
        return round($this->shopCalculations->totalPrice, 2);
    }

    /**
     * Returns total tax of all the items in cart.
     *
     * @return float
     */
    public function getTotalTaxAttribute()
    {
        if (empty($this->shopCalculations)) $this->runCalculations();
        return round($this->shopCalculations->totalTax + ($this->totalPrice * config('shop.tax', 0.0)), 2);
    }

    /**
     * Returns total tax of all the items in cart.
     *
     * @return float
     */
    public function getTotalShippingAttribute()
    {
        if (empty($this->shopCalculations)) $this->runCalculations();
        return round($this->shopCalculations->totalShipping, 2);
    }

    /**
     * Returns total discount amount based on all coupons applied.
     *
     * @return float
     */
    public function getTotalDiscountAttribute()
    { /* TODO */
    }

    /**
     * Returns total amount to be charged base on total price, tax and discount.
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        if (empty($this->shopCalculations)) $this->runCalculations();
        return $this->totalPrice + $this->totalTax + $this->totalShipping;
    }

    /**
     * Returns formatted total price of all the items in cart.
     *
     * @return string
     */
    public function getDisplayTotalPriceAttribute()
    {
        return $this->format($this->totalPrice);
    }

    /**
     * Returns formatted total tax of all the items in cart.
     *
     * @return string
     */
    public function getDisplayTotalTaxAttribute()
    {
        return $this->format($this->totalTax);
    }

    /**
     * Returns formatted total tax of all the items in cart.
     *
     * @return string
     */
    public function getDisplayTotalShippingAttribute()
    {
        return $this->format($this->totalShipping);
    }

    /**
     * Returns formatted total discount amount based on all coupons applied.
     *
     * @return string
     */
    public function getDisplayTotalDiscountAttribute()
    { /* TODO */
    }

    /**
     * Returns formatted total amount to be charged base on total price, tax and discount.
     *
     * @return string
     */
    public function getDisplayTotalAttribute()
    {
        return $this->format($this->total);
    }

    /**
     * Returns cache key used to store calculations.
     *
     * @return string.
     */
    public function getCalculationsCacheKeyAttribute()
    {
        return 'shop_' . $this->table . '_' . $this->getKey() . '_calculations';
    }

    /**
     * Runs calculations.
     */
    private function runCalculations()
    {
        if (!empty($this->shopCalculations)) return $this->shopCalculations;
        $cacheKey = $this->calculationsCacheKey;
        if ($this->cache_calculations && Cache::has($cacheKey)) {
            $this->shopCalculations = Cache::get($cacheKey);
            return $this->shopCalculations;
        }
        $this->shopCalculations = DB::table($this->table)
            ->select([
                DB::raw('sum(' . $this->getItemTableName() . '.quantity) as itemCount'),
                DB::raw('sum(' . $this->getItemTableName() . '.price * ' . $this->getItemTableName() . '.quantity) as totalPrice'),
                DB::raw('sum(' . $this->getItemTableName() . '.tax * ' . $this->getItemTableName() . '.quantity) as totalTax'),
                DB::raw('sum(' . $this->getItemTableName() . '.shipping * ' . $this->getItemTableName() . '.quantity) as totalShipping')
            ])
            ->join(
                $this->getItemTableName(),
                $this->getItemTableName() . '.' . ($this->table == $this->getOrderTableName() ? 'order_id' : $this->table . '_id'),
                '=',
                $this->table . '.id'
            )
            ->where($this->table . '.id', $this->attributes['id'])
            ->first();
        if ($this->cache_calculations) {
            Cache::put(
                $cacheKey,
                $this->shopCalculations,
                $this->cache_calculations_minutes
            );
        }
        return $this->shopCalculations;
    }

    /**
     * Resets cart calculations.
     */
    public function resetCalculations()
    {
        $this->shopCalculations = null;
        if ($this->cache_calculations) {
            Cache::forget($this->calculationsCacheKey);
        }
    }


}
