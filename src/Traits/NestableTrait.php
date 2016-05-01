<?php namespace AwatBayazidi\Abzar\Traits;

use AwatBayazidi\Foundation\Tree\NestableCollection;

trait NestableTrait
{
    /**
     * Return a custom nested collection.
     *
     * @param array $models
     *
     * @return NestableCollection
     */
    public function newCollection(array $models = [])
    {
        return new NestableCollection($models);
    }
}
