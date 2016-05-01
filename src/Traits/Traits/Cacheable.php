<?php

namespace Tshafer\Traits\Traits;

use Illuminate\Support\Facades\Cache;
use Tshafer\Traits\Eloquent\Builder;

trait Cacheable
{
    /**
     * Set the cache expiry time.
     *
     * @var int
     */
    public $cacheExpiry = 1440;

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            Cache::tags($model->getTable())->forget($model->{$model->getKeyName()});
        }, -1);

        static::saved(function ($model) {
            Cache::tags($model->getTable())->forget($model->{$model->getKeyName()});
        }, -1);

        static::deleted(function ($model) {
            Cache::tags($model->getTable())->forget($model->{$model->getKeyName()});
        }, -1);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \PulkitJalan\Cacheable\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}