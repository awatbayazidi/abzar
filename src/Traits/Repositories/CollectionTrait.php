<?php namespace AwatBayazidi\Abzar\Traits\Repositories;

use Illuminate\Support\Facades\Schema;

trait CollectionTrait
{
    /**
     * @param $value
     * @param null $key
     *
     * @return mixed
     */
    public function lists($value, $key = null)
    {
        return $this->getModel()->lists($value, $key);
    }

    /**
     * @param array $searchQuery
     *
     * @return mixed
     */
    public function allOrSearch($searchQuery = null, $columns = ['*'])
    {
        if (is_null($searchQuery)) {
            return $this->all();
        }
        return $this->search($searchQuery, $columns);
    }

    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        $collection = $this->getModel()->get($columns);

        $this->makeModel();
        return $collection;
    }

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function search($input, $columns = ['*'])
    {
        $query = $this->getModel()->query();
        $_columns = Schema::getColumnListing($this->getTable());
        $attributes = array();
        foreach ($_columns as $attribute) {
            if (isset($input[$attribute]) and !empty($input[$attribute])) {
                $query->where($attribute, $input[$attribute]);
                $attributes[$attribute] = $input[$attribute];
            } else {
                $attributes[$attribute] = null;
            }
        };
        //  dd([$query->get(), $attributes]);
        return $query->get($columns);

    }

    /**
     * @param int $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($perPage = null, $columns = ['*'])
    {
        if (is_null($perPage)) $this->getPerPage();

        $collection = $this->getModel()->paginate($perPage, $columns);

        $this->makeModel();

        return $collection;
    }

    /**
     * @param int $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function simplePaginate($perPage = null, $columns = ['*'])
    {
        if (is_null($perPage)) $this->getPerPage();

        $collection = $this->getModel()->simplePaginate($perPage, $columns);

        $this->makeModel();

        return $collection;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function listBy($key, $value)
    {
        return $this->get([
            $key, explode('.', $value)[0],
        ])->keyBy($key)->map(function ($item, $key) use ($value) {
            return array_get($item->toArray(), $value);
        });
    }
}
