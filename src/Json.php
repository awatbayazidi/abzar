<?php namespace AwatBayazidi\Abzar;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Filesystem\Filesystem;
use JsonSerializable;

class Json implements Arrayable, Jsonable, JsonSerializable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $path;
    protected $filesystem;
    protected $attributes;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($path, Filesystem $filesystem = null)
    {
        $this->path         = (string) $path;
        $this->filesystem   = $filesystem ?: new Filesystem;
        $this->attributes   = Collection::make($this->getAttributes());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = (string) $path;
        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public static function make($path, Filesystem $filesystem = null)
    {
        return new static($path, $filesystem);
    }

    public function getContents()
    {
        return $this->filesystem->get($this->getPath());
    }

    public function update(array $data)
    {
        $this->attributes = new Collection(array_merge(
            $this->attributes->toArray(),
            $data
        ));
        return $this->save();
    }

    public function __call($method, $arguments = [])
    {
        return method_exists($this, $method) ? call_user_func_array([$this, $method], $arguments) : $this->attributes->get($method);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($key, $default = null)
    {
        return $this->attributes->get($key, $default);
    }

    public function set($key, $value)
    {
        $this->attributes->offsetSet($key, $value);
        return $this;
    }

    public function save()
    {
        return $this->filesystem->put($this->getPath(), $this->toJsonPretty());
    }

    public function getAttributes()
    {
        return json_decode($this->getContents(), true);
    }

    public function toJsonPretty(array $data = null)
    {
        return json_encode($data ?: $this->attributes, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function __toString()
    {
        return $this->getContents();
    }

    public function toArray()
    {
        return $this->getAttributes();
    }


    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
