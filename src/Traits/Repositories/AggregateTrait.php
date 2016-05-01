<?php namespace AwatBayazidi\Abzar\Traits\Repositories;


trait AggregateTrait
{
    /**
     * @return mixed
     */
    public function count()
    {
        return $this->getModel()->count();
    }

    /**
     * @param $column
     *
     * @return mixed
     */
    public function max($column)
    {
        return $this->getModel()->max($column);
    }

    /**
     * @param $column
     *
     * @return mixed
     */
    public function min($column)
    {
        return $this->getModel()->min($column);
    }

    /**
     * @param $column
     *
     * @return mixed
     */
    public function avg($column)
    {
        return $this->getModel()->avg($column);
    }

    /**
     * @param $column
     *
     * @return mixed
     */
    public function sum($column)
    {
        return $this->getModel()->sum($column);
    }

}
