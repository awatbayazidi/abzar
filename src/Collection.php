<?php namespace AwatBayazidi\Abzar;


use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{

    public function reset()
    {
        $this->items = [];
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }



}
