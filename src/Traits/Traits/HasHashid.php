<?php

namespace Tshafer\Traits\Traits;

/**
 * Class HasHashid.
 */
trait HasHashid
{
    /**
     * @var string
     */
    protected static $hashidColumn = 'hashid';

    /**
     * Generate a HashID.
     */
    public static function bootHasHashid()
    {
        static::creating(function ($model) {
            $model->{static::$hashidColumn} = uuid4();
        });
    }
}
