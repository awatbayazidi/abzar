<?php namespace AwatBayazidi\Abzar\Traits\Repositories;


use Illuminate\Database\Eloquent\Model;


trait CrudlTrait
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create($data)
    {
        return $this->getModel()->create($data);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function saveModel(array $data)
    {
        foreach ($data as $key => $value) {
            $this->getModel()->$key = $value;
        }
        $model = $this->getModel()->save();
        $this->makeModel();

        return $model;
    }

    /**
     * @param $id
     * @param array  $data
     * @param string $column
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function update($id, $data, $column = 'id')
    {
        $model = $this->requireBy($column, $id);
        return $this->updateModel($model, $data);
    }

    /**
     * @param $id
     * @param array  $data
     * @param string $column
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function updateFill($id,$data, $column = 'id')
    {
        $model = $this->requireBy($column, $id);

        if (!$model = $model->fill($data)->save()) {
            throw new \Exception('Could not be saved');
        }

        $this->makeModel();

        return $model;
    }


    /**
     * @param Model $model
     * @param array $data
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function updateModel(Model $model,$data)
    {
        if (!$model->update($data)) {
            throw new \Exception('Could not be saved');
        }
        $this->makeModel();
        return $model;
    }

    /**
     * @param $ids
     *
     * @return mixed
     */
    public function destroy($ids)
    {
        return $this->getModel()->destroy($ids);
    }

    public function delete($id)
    {
        $model = $this->findById($id);
        dd($model);
        if (!is_null($model)) {
            $model->delete();
            return true;
        }else{
            return $this->forceDelete($id);
        }
    }

    /**
     * @return mixed
     */
    public function truncate()
    {
        return $this->getModel()->delete();
    }


    public function forceDelete($id)
    {
        $model = $this->withTrashed()->findById($id);
        if (!is_null($model)) {
            $model->forceDelete();
            return true;
        }
        return false;
    }


    public function restore($id)
    {
        $model = $this->getModel()->withTrashed()->where('id', $id)->restore();
        if (!is_null($model)) {
            return true;
        }
        return false;
    }


    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->getModel()->firstOrCreate($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function firstOrNew(array $attributes)
    {
        return $this->getModel()->firstOrNew($attributes);
    }

    /**
     * @param $relation
     * @param array $columns
     *
     * @return mixed
     */
    public function has($relation, $columns = ['*'])
    {
        $collection = $this->getModel()->has($relation)->get($columns);

        $this->makeModel();

        return $collection;
    }

    /**
     * @param Model $model
     * @param $relationship
     * @param array $attributes
     *
     * @return mixed
     */
    public function saveHasOneOrMany(Model $model, $relationship, array $attributes)
    {
        $relationshipModel = get_class($model->{$relationship}()->getModel());
        $relationshipModel = new $relationshipModel($attributes);

        return $model->{$relationship}()->save($relationshipModel);
    }
}
