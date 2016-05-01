<?php namespace AwatBayazidi\Abzar;


class Stub
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $path;
    protected static $basePath = '';
    protected $replaces = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($path, array $replaces = [])
    {
        $this->setPath($path);
        $this->setReplaces($replaces);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    public function getPath()
    {
        $path = $this->path;
        if ( ! empty(static::$basePath)) {
            $path = static::$basePath . DS . ltrim($path, DS);
        }
        return $path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public static function getBasePath()
    {
        return static::$basePath;
    }

    public static function setBasePath($path)
    {
        static::$basePath = $path;
    }

    public function getReplaces()
    {
        return $this->replaces;
    }

    public function setReplaces(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    public function replaces(array $replaces = [])
    {
        return $this->setReplaces($replaces);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public static function create($path, array $replaces = [])
    {
        return new static($path, $replaces);
    }

    public static function createFromPath($path, array $replaces = [])
    {
        $stub = static::create($path, $replaces);
        $stub->setBasePath('');
        return $stub;
    }

    public function render()
    {
        return $this->getContents();
    }

    public function save($filename)
    {
        return $this->saveTo(self::getBasePath(), $filename);
    }

    public function saveTo($path, $filename)
    {
        return file_put_contents($path . DS . $filename, $this->render());
    }

    public function getContents()
    {
        $contents = file_get_contents($this->getPath());
        foreach ($this->getReplaces() as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }
        return $contents;
    }

    public function __toString()
    {
        return $this->render();
    }
}
