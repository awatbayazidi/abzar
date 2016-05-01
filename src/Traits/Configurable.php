<?php namespace AwatBayazidi\Abzar\Traits;


trait Configurable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $configs = [];

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    protected function setConfigs(array $configs)
    {
        $this->configs = $configs;
        return $this;
    }

    protected function getConfig($key, $default = null)
    {
        return array_get($this->configs, $key, $default);
    }
}
