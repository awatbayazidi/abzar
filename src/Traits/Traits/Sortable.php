<?php

namespace Tshafer\Traits\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * @param Builder $query
     * @param null    $column
     * @param string  $dir
     *
     * @return Builder
     */
    public function scopeSorted(Builder $query, $column = null, $dir = 'asc')
    {
        return $query->orderBy($this->getSortColumn($column), $dir);
    }

    /**
     * @param null $override
     *
     * @return string
     */
    protected function getSortColumn($override = null)
    {
        return $this->sortBy ?: $override ?: 'created_at';
    }
}
