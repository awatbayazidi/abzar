<?php namespace AwatBayazidi\Abzar\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Authentic
 * @package AwatBayazidi\Abzar\Facades
 */
class Authentic extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'authentic';
    }
}
