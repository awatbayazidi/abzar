<?php namespace AwatBayazidi\Abzar\Facades\Bootstrapper;

use Illuminate\Support\Facades\Facade;

class Button extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'awatbayazidi::button';
    }
}
