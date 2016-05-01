<?php

namespace Tshafer\Traits\Traits;

trait ReturnsArrays
{
    /**
     * Fills an array with key value pairs obtained from query results.
     *
     * @method  scopeFillArray
     *
     * @param Illuminate\Database\Query\Builder $query        The query being scoped
     * @param string                            $key_column   The column to be used as the key
     * @param string                            $value_column The column to be used as the value
     *
     * @return array The filled array
     */
    public function scopeFillArray($query, $key_column = 'id', $value_column = 'name')
    {
        // First get the results from the query
        $results = $query->get();

        // Initialise an empty array to store our results in
        $rtn = [];

        /** @var Illuminate\Database\Eloquent\Model $result */
        foreach ($results as $result) {
            // Add the requested key and value to our array
            $rtn[$result->{$key_column}] = $result->{$value_column};
        }

        return $rtn;
    }

    /**
     * Group an array based on one of it's values.
     *
     * @method  scopeArrayGrouped
     *
     * @param Illuminate\Database\Query\Builder $query      The query being scoped
     * @param                                   $key_column $group_key The key whose value we'll group by
     *
     * @return array The grouped array
     */
    public function scopeArrayGrouped($query, $key_column)
    {
        // First get the results from the query
        $results = $query->get();

        // Initialise an empty array to store our results in
        $rtn = [];

        /** @var Illuminate\Database\Eloquent\Model $result */
        foreach ($results as $result) {
            $group_key = $result->{$key_column};

            // If the group key has not already been set in our results,
            // initialise it as an empty array
            if (!isset($rtn[$group_key])) {
                $rtn[$group_key] = [];
            }

            // Add the model to the query
            $rtn[$group_key][] = $result;
        }

        return $rtn;
    }
}
