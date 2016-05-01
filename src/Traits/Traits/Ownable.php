<?php

namespace Tshafer\Traits;

/**
 * Class Ownable.
 */
trait Ownable
{
    /**
     * Save user_id when new records are created.
     */
    public static function bootOwnedTrait()
    {
        static::registerModelEvent('creating', function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = user('id');
            }
        });
    }

    /**
     * Build query to only find records owned by currently logged in user
     * or pass in a userId as parameter.
     *
     * @param      $query
     * @param null $userId
     *
     * @return mixed
     */
    public function scopeOwned($query, $userId = null)
    {
        if (is_null($userId)) {
            $userId = user('id');
        }

        return $query->where('user_id', $userId);
    }

    /**
     * Build query to only find records owned by current logged in user
     * or pass in a userId as parameter.
     *
     * @param      $query
     * @param null $userId
     *
     * @return mixed
     */
    public function scopeNotOwned($query, $userId = null)
    {
        if (is_null($userId)) {
            $userId = user('id');
        }

        return $query->where('user_id', '<>', $userId);
    }

    /**
     * Is the currently logged in user the owner.
     *
     * @return bool
     */
    public function getAmOwnerAttribute()
    {
        if (!auth()) {
            return false;
        }

        return auth()->user->id == $this->attributes['user_id'];
    }
}
