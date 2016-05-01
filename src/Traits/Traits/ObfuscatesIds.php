<?php

namespace Tshafer\Traits\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ObfuscatesIds
{
    /**
     * @var int
     */
    private static $max_int = 2147483647;

    /**
     * @var int
     */
    private static $prime;

    /**
     * @var int
     */
    private static $inverse;

    /**
     * @var int
     */
    private static $xor;

    /**
     * @var int
     */
    public $__id;

    /**
     * Loads variables needed for obfuscation from environment config.
     * (Sensible defaults provided to avoid breaking model if not set).
     *
     * @method  prepare
     */
    public static function prepare()
    {
        static::$prime = env('OBFUSCATE_ID_PRIME', 2123809381);
        static::$inverse = env('OBFUSCATE_ID_INVERSE', 1885413229);
        static::$xor = env('OBFUSCATE_ID_RANDOM', 146808189);
    }

    /**
     * Encode an integer.
     *
     * @param int $value
     *
     * @return int
     */
    public static function encode($value)
    {
        if (!is_numeric($value)) {
            return;
        }

        if (!static::$prime) {
            static::prepare();
        }

        return (((int) $value * static::$prime) & static::$max_int) ^ static::$xor;
    }

    /**
     * Decode an integer.
     *
     * @param int $value
     *
     * @return int
     */
    public static function decode($value)
    {
        if (!is_numeric($value)) {
            return;
        }

        if (!static::$inverse) {
            static::prepare();
        }

        return (((int) $value ^ static::$xor) * static::$inverse) & static::$max_int;
    }

    /**
     * Decodes an obfuscated ID when writing to the database.
     *
     * @method  setIdAttribute
     *
     * @param int $value The obfuscated ID for the model
     *
     * @return int The decoded ID
     */
    public function setIdAttribute($value)
    {
        return static::decode($value);
    }

    /**
     * Encodes a database ID before presenting it publicly.
     *
     * @method  getIdAttribute
     *
     * @return int The obfuscated ID
     */
    public function getIdAttribute()
    {
        // Set the database ID on another property, so it
        // can be retrieved if we need it
        $this->__id = $value;

        return static::encode($value);
    }

    /**
     * Return the database ID directly, as used internally by Laravel.
     *
     * @method  getKey
     *
     * @return int The decoded ID
     */
    public function getKey()
    {
        return static::decode($this->id);
    }

    /**
     * Replaces Eloquent's normal find() function, converting an obfuscated
     * ID to the decoded database ID before performing the query.
     *
     * @method  find
     *
     * @param int   $id      The obfuscated ID
     * @param array $columns The columns to select
     *
     * @return $this|null The model being searched for
     */
    public static function find($id, $columns = ['*'])
    {
        $klass = new self();

        return $klass->where('id', '=', static::decode($id))->first($columns);
    }

    /**
     * Replaces Eloquent's normal findOrFail() function, converting an obfuscated
     * ID to the decoded database ID before performing the query.
     *
     * @method  find
     *
     * @param int   $id      The obfuscated ID
     * @param array $columns The columns to select
     *
     * @return $this|null The model being searched for
     */
    public static function findOrFail($id, $columns = ['*'])
    {
        if ($model = static::find($id)) {
            return $model;
        }

        throw new ModelNotFoundException('No query results for model ['.get_class().'].');
    }
}
