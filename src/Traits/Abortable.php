<?php namespace AwatBayazidi\Abzar\Traits;

trait Abortable
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */

    protected static function pageNotFound($message = 'Page not Found', array $headers = [])
    {
        abort(404, $message, $headers);
    }

    protected static function accessNotAllowed($message = 'Access denied !', array $headers = [])
    {
        abort(403, $message, $headers);
    }
}
