<?php namespace AwatBayazidi\Abzar\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * @see \AwatBayazidi\Payam\Payam
 */
class Payam extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payam';
    }

} 