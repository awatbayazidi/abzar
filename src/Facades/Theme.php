<?php namespace AwatBayazidi\Abzar\Facades;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade
{
    /**
     * Get Facade Accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'themes';
    }
}
