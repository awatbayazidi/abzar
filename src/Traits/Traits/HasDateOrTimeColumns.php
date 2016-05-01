<?php

namespace Tshafer\Traits\Traits;

use Carbon\Carbon;

/**
 * Class HasDateOrTimeColumns.
 */
trait HasDateOrTimeColumns
{
    /**
     * Set the attribute - checking for date/time columns.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return null|void
     */
    public function setAttribute($key, $value)
    {
        if ($this->isDateColumn($key)) {
            return $this->setDateColumn($key, $value);
        }

        if ($this->isTimeColumn($key)) {
            return $this->setTimeColumn($key, $value);
        }

        parent::setAttribute($key, $value);
    }

    /**
     * Set the date column.
     *
     * @param $key
     * @param $value
     */
    protected function setDateColumn($key, $value)
    {
        $this->attributes[$key] = Carbon::createFromFormat($this->getDateUserFormat(),
          $value);
    }

    /**
     * Set the time column.
     *
     * @param $key
     * @param $value
     */
    protected function setTimeColumn($key, $value)
    {
        $this->attributes[$key] = Carbon::createFromFormat($this->getTimeUserFormat(),
          $value);
    }

    /**
     * Get the attribute value - checking for date/time columns.
     *
     * @param string $key
     *
     * @return mixed|static
     */
    protected function getAttributeValue($key)
    {
        if ($this->isDateColumn($key)) {
            return $this->getDateColumnValue($key);
        }

        if ($this->isTimeColumn($key)) {
            return $this->getTimeColumnValue($key);
        }

        return parent::getAttributeValue($key);
    }

    /**
     * Get the date column value.
     *
     * @param $key
     *
     * @return static
     */
    protected function getDateColumnValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        $carbon = Carbon::createFromFormat($this->getDateSourceFormat(),
          $value)->startOfDay();

        $carbon->setToStringFormat($this->getDateUserFormat());

        return $carbon;
    }

    /**
     * Get the time column value.
     *
     * @param $key
     *
     * @return static
     */
    protected function getTimeColumnValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        $carbon = Carbon::createFromFormat($this->getTimeSourceFormat(), $value);

        $carbon->setToStringFormat($this->getTimeUserFormat());

        return $carbon;
    }

    /**
     * Whether or not the $key should be a date.
     *
     * @param $key
     *
     * @return bool
     */
    protected function isDateColumn($key)
    {
        return in_array($key, $this->getDateColumns());
    }

    /**
     * Get the date colums.
     *
     * @return array
     */
    protected function getDateColumns()
    {
        return $this->dateColumns ?: [];
    }

    /**
     * Get the Database Date Format.
     *
     * @return string
     */
    protected function getDateSourceFormat()
    {
        return $this->dateSourceFormat ?: 'Y-m-d';
    }

    /**
     * Get the User Date Format.
     *
     * @return string
     */
    protected function getDateUserFormat()
    {
        return $this->dateUserFormat ?: 'm/d/Y';
    }

    /**
     * Whether or not the $key should be a time.
     *
     * @param $key
     *
     * @return bool
     */
    protected function isTimeColumn($key)
    {
        return in_array($key, $this->getTimeColumns());
    }

    /**
     * Get the time columns.
     *
     * @return array
     */
    protected function getTimeColumns()
    {
        return $this->timeColumns ?: [];
    }

    /**
     * Get the Database Time Format.
     *
     * @return string
     */
    public function getTimeSourceFormat()
    {
        return $this->timeSourceFormat ?: 'H:i:s';
    }

    /**
     * Get the User Time Format.
     *
     * @return string
     */
    public function getTimeUserFormat()
    {
        return $this->timeUserFormat ?: 'h:i a';
    }
}
