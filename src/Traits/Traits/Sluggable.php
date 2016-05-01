<?php

namespace Tshafer\Traits\Traits;

trait Sluggable
{
    /**
     * Convert the value into a slug using a regexp replacement.
     *
     * @param string $value
     *
     * @return string
     */
    public function makeSlug($value)
    {
        $value = preg_replace('/[^a-zA-Z0-9\-\_]+/', '_', $value);

        return strtolower($value);
    }

    /**
     * Get the key that defines the model's sluggable attribute.
     *
     * @return string
     */
    public function getSlugKey()
    {
        return $this->slug ?: 'slug';
    }

    /**
     * Set the key that defines the model's sluggable attribute.
     *
     * @param string $key
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function setSlugKey($key)
    {
        $this->slug = $key;

        return $this;
    }

    /**
     * Get the value assigned to the sluggable key.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return array_get($this->attributes, $this->getSlugKey());
    }

    /**
     * Mutate the value to a slug format and assign to the sluggable key.
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        array_set($this->attributes, $this->getSlugKey(), $this->makeSlug($value));
    }
}
