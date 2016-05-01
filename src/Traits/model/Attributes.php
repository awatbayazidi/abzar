<?php namespace AwatBayazidi\Abzar\model;

/**
 * Class Attributes.
 */
trait Attributes
{
    /**
     * Format Created At Attribute.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return carbon($value)->format('m/d/Y - h:ia');
    }

    /**
     * Format Updated At Attribute.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getUpdatedAtAttribute($value)
    {
        return carbon($value)->format('m/d/Y - h:ia');
    }

    /**
     * Format Deleted At Attribute.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getDeletedAtAttribute($value)
    {
        return carbon($value)->format('m/d/Y - h:ia');
    }

    /**
     * @return null|string
     */
    public function getHumanCreatedAtAttribute()
    {
        return $this->getHumanTimestampAttribute('created_at');
    }

    /**
     * @param $column
     *
     * @return null|string
     */
    protected function getHumanTimestampAttribute($column)
    {
        if ($this->attributes[$column]) {
            return carbon($this->attributes[$column])->diffForHumans();
        }
    }

    /**
     * @return null|string
     */
    public function getHumanUpdatedAtAttribute()
    {
        return $this->getHumanTimestampAttribute('updated_at');
    }

    /**
     * @return null|string
     */
    public function getHumanDeletedAtAttribute()
    {
        return $this->getHumanTimestampAttribute('deleted_at');
    }

    /**
     * @param $value
     *
     * @return mixed|string
     */
    public function getPhoneAttribute($value)
    {
        return $this->phone($value);
    }

    /**
     * @param $value
     *
     * @return mixed|string
     */
    public function getFaxAttribute($value)
    {
        return $this->phone($value);
    }

    /**
     * @param $phone
     *
     * @return bool|string
     */
    private function phone($phone)
    {
        if (!$phone) {
            return false;
        }
        $phone = (int) $phone;
        $string = substr($phone, 0, 3);
        $string .= '-';
        $string .= substr($phone, 3, 3);
        $string .= '-';
        $string .= substr($phone, 6);

        return $string;
    }

    /**
     * Hash the User's Password.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $value = app('hash')->needsRehash($value) ? bcrypt($value) : $value;

        $this->attributes['password'] = $value;
    }
}
