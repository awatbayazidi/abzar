<?php namespace AwatBayazidi\Abzar\Traits;


use AwatBayazidi\Foundation\Support\Uploader;

trait UploaderTrait
{


    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        if(in_array($key, $this->file_fields))
        {
            $value = (new Uploader())->url($this->attributes[$key]);
        }
        return $value;
    }

    public function setAttribute($key, $value)
    {

        if (in_array($key, $this->file_fields) && $value)
        {
            if(empty($value))
                return parent::setAttribute($key, $value);

            if(is_file($value)) {
                return  $this->attributes[$key] = (new Uploader())->upload($value)->get('filename');
            }

        }
        return parent::setAttribute($key, $value);
    }

}
