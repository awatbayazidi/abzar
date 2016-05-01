<?php

namespace Tshafer\Traits\Traits;

/**
 * Class Imagable.
 */
trait Imagable
{
    /**
     * Set Image Attribute.
     *
     * @param string $value
     */
    public function setImageAttribute($value)
    {
        $this->uploadImage($value);
    }

    /**
     * @param $value
     */
    protected function uploadImage($value)
    {
        if (isset($this->attributes[$value]) && $this->attributes[$value] != '') {
            $filename = public_path().'/uploads/'.$this->attributes[$value];

            if (filesystem()->exists($filename)) {
                filesystem()->delete($filename);
            }
        }

        if ($value == '') {
            $this->attributes[$value] = '';
        } else {
            $filename = time().str_random(10);

            $value->move(public_path().'/uploads/', $filename);

            $this->attributes[$value] = $filename;
        }
    }
}
