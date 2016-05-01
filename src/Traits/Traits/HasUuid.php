<?php

namespace Tshafer\Traits;

use Rhumsaa\Uuid\Uuid;

/**
 * Class HasUuid.
 */
trait HasUuid
{
    /**
     * @var string
     */
    protected static $uuidColumn = 'uuid';

    /**
     * Boot the Uuid trait for the model.
     */
    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            $model->{static::$uuidColumn} = Uuid::uuid4();
        });
    }
}
