<?php

namespace Tshafer\Traits\Traits;

use Illuminate\Http\Request;

/**
 * Class BooleanInterpreter.
 */
trait BooleanInterpreter
{
    /**
     * @param Request $request
     */
    public function interpretBooleans(Request $request)
    {
        foreach ($this->getBooleans() as $boolean) {
            $this->{$boolean} = $request->has($boolean);
        }
    }

    /**
     * @param $attribute
     *
     * @return string
     */
    public function boolean($attribute)
    {
        return $this->attributes[$attribute] ? 'Yes' : 'No';
    }

    /**
     * @return array
     */
    protected function getBooleans()
    {
        return $this->booleans ?: [];
    }
}
