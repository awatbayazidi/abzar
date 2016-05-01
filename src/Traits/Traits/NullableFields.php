<?php

namespace Tshafer\Traits\Traits;

trait NullableFields
{
    /**
     * Boot the trait, add a saving observer.
     *
     * When saving the model, we iterate over its attributes and for any attribute
     * marked as nullable whose value is empty, we then set its value to null.
     */
    protected static function bootNullableFields()
    {
        static::saving(function ($model) {
            foreach ($model->nullableFromArray($model->getAttributes()) as $column => $value) {
                $model->setAttribute($column, $model->nullIfEmpty($value));
            }
        });
    }

    /**
     * If value is empty, return null, otherwise return the original input.
     *
     * @param string $value
     *
     * @return null|string
     */
    protected function nullIfEmpty($value)
    {
        return trim($value) === '' ? null : $value;
    }

    /**
     * Get the nullable attributes of a given array.
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function nullableFromArray(array $attributes = [])
    {
        if (count($this->nullable) > 0) {
            return array_intersect_key($attributes, array_flip($this->nullable));
        }

        // Assume no fields are nullable
        return [];
    }
}
