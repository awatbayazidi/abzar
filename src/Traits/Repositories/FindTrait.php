<?php namespace AwatBayazidi\Abzar\Traits\Repositories;


use AwatBayazidi\Foundation\Model\Repository\Criteria\OrderBy;
use AwatBayazidi\Foundation\Model\Repository\Criteria\WithTrashed;
use Illuminate\Database\Eloquent\Model;


trait FindTrait
{
    /**
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {

        $model = $this->getQuery()->find($id, $columns);
        $this->makeModel();
        return $model;
    }

    public function findById($id, $columns = ['*'])
    {
        return $this->find($id, $columns);
    }

    /**
     * @param $column
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findBy($column, $value, $columns = ['*'])
    {
        $this->setQuery($this->getQuery()->where($column, '=', $value));
        return $this->first($columns);
    }

    /**
     * @param $column
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findManyBy($column, $value, $columns = ['*'])
    {
        $collection = $this->getQuery()->where($column, '=', $value)->get($columns);
        $this->makeModel();
        return $collection;
    }

    /**
     * @param $where
     * @param array $columns
     * @param bool  $or
     *
     * @return mixed
     */
    public function findWhere($where, $columns = ['*'], $or = false)
    {
        $model = $this->getQuery();
        foreach ($where as $field => $value) {
            if ($value instanceof \Closure) {
                $model = (!$or)
                    ? $model->where($value)
                    : $model->orWhere($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;

                    $model = (!$or)
                        ? $model->where($field, $operator, $search)
                        : $model->orWhere($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;

                    $model = (!$or)
                        ? $model->where($field, '=', $search)
                        : $model->orWhere($field, '=', $search);
                }
            } else {
                $model = (!$or)
                    ? $model->where($field, '=', $value)
                    : $model->orWhere($field, '=', $value);
            }
        }

        $collection = $model->get($columns);

        $this->makeModel();

        return $collection;
    }

    /**
     * @param $column
     * @param array  $values
     * @param string $boolean
     * @param bool   $not
     *
     * @return mixed
     */
    public function findWhereBetween($column, array $values, $boolean = 'and', $not = false)
    {
        $collection = $this->getQuery()->whereBetween($column, $values, $boolean, $not)->get();
        $this->makeModel();
        return $collection;
    }

    /**
     * @param $column
     * @param array  $values
     * @param string $boolean
     * @param bool   $not
     *
     * @return mixed
     */
    public function findWhereIn($column, array $values, $columns = ['*'])
    {
        $collection = $this->getQuery()->whereIn($column, $values)->get($columns);
        $this->makeModel();
        return $collection;
    }

    /**
     * @param $column
     * @param array  $values
     * @param string $boolean
     * @param bool   $not
     *
     * @return mixed
     */
    public function findWhereNotIn($column, array $values, $columns = ['*'])
    {
        $collection = $this->getQuery()->whereNotIn($column, $values)->get($columns);
        $this->makeModel();
        return $collection;
    }

    /**
     * @param $column
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findFirstBy($column, $value, $columns = ['*'])
    {
        $model = $this->getQuery()->where($column, '=', $value)->first($columns);
        $this->makeModel();

        return $model;
    }

    /**
     * @param $column
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findLastBy($column, $value, $columns = ['*'])
    {
        $model = $this->getQuery()->where($column, '=', $value)->orderBy('created_at', 'desc')->first($columns);

        $this->makeModel();
        return $model;
    }

    /**
     * @param $column
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findManyLastBy($column, $value, $columns = ['*'])
    {
        $model = $this->findLastBy()->where($column, '=', $value)->orderBy('created_at', 'desc')->get($columns);

        $this->makeModel();
        return $model;
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    public function findNext(Model $model)
    {
        $next = $this->findLastBy()
                     ->where('created_at', '>=', $model->created_at)
                     ->where('id', '<>', $model->id)
                     ->orderBy('created_at', 'asc')
                     ->first();

        $this->makeModel();

        return $next;
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    public function findPrevious(Model $model)
    {
        $prev = $this->findLastBy()
                     ->where('created_at', '<=', $model->created_at)
                     ->where('id', '<>', $model->id)
                     ->orderBy('created_at', 'desc')
                     ->first();

        $this->makeModel();

        return $prev;
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function findRecentlyCreated($perPage = null, $columns = ['*'])
    {
        if(is_null($perPage)){$this->getPerPage();}
        $model = $this->findLastBy()->orderBy('created_at', 'desc')->paginate($perPage, $columns);
        return $model;
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function findRecentlyUpdated($perPage = null, $columns = ['*'])
    {
        if(is_null($perPage)){$this->getPerPage();}
        $model = $this->findLastBy()->orderBy('updated_at', 'desc')->paginate($perPage, $columns);
        return $model;
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function findRecentlyDeleted($perPage = null, $columns = ['*'])
    {
        if(is_null($perPage)){$this->getPerPage();}
        $model = $this->findLastBy()->withTrashed()->orderBy('deleted_at', 'desc')->paginate($perPage, $columns);
        return $model;
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function findWithTrashed($perPage = null, $columns = ['*'])
    {
        if(is_null($perPage)){$this->getPerPage();}
        $model = $this->findLastBy()->withTrashed()->paginate($perPage, $columns);
        return $model;
    }



    public function findOnlyTrashedBy($column, $value, $columns = ['*'])
    {
        $collection = $this->findLastBy()->onlyTrashed()->where($column, '=', $value)->get($columns);
        $this->makeModel();

        return $collection;
    }

    public function findTrashedBy($column, $value, $columns = ['*'])
    {
        $collection = $this->findLastBy()->withTrashed()->where($column, '=', $value)->get($columns);
        $this->makeModel();

        return $collection;
    }



    public function findOnlyTrashed()
    {
        $model = $this->getModel()->onlyTrashed()->get($columns = ['*']);
        $this->makeModel();

        return $model;
    }

}
