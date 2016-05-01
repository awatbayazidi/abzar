<?php namespace AwatBayazidi\Abzar\Traits\Repositories;

use Carbon\Carbon;
use AwatBayazidi\Foundation\Support\DateTime;


trait DateTimeFindTrait
{
    /**
     * @param $key
     * @param $value
     * @param $column
     * @param $range
     *
     * @return mixed
     */
    public function findWhereDateTimeBetween($key, $value, $column, $range)
    {
        return $this->getModel()
                    ->where($key, '=', $value)
                    ->whereBetween($column, $range)
                    ->get();
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromToday($key, $value)
    {
        return $this->findFromThisDay($key, $value);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromYesterday($key, $value)
    {
        return $this->findFromLastDay($key, $value);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastSevenDays($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDaysAgo(6, Carbon::now()->endOfDay())
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastThirtyDays($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDaysAgo(29, Carbon::now()->endOfDay())
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastFourWeeks($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDaysAgo(Carbon::now()->daysInMonth, Carbon::Now()->endOfDay())
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromThisDay($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDaysAgo(0)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromThisWeek($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getWeeksAgo(0)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromThisMonth($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getMonthsAgo(0)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromThisYear($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getYearsAgo(0)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromThisDecade($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDecadesAgo(0)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromThisCentury($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getCenturiesAgo(0)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastDay($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDaysAgo(1)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastWeek($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getWeeksAgo(1)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastMonth($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getMonthsAgo(1)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastYear($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getYearsAgo(1)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastDecade($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDecadesAgo(1)
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findFromLastCentury($key, $value)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getCenturiesAgo(1)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function findFromDaysAgo($key, $value, $ago, $endDateTime = null)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDaysAgo($ago, $endDateTime)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function findFromWeeksAgo($key, $value, $ago, $endDateTime = null)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getWeeksAgo($ago, $endDateTime)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function findFromMonthsAgo($key, $value, $ago, $endDateTime = null)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getMonthsAgo($ago, $endDateTime)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function findFromYearsAgo($key, $value, $ago, $endDateTime = null)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getYearsAgo($ago, $endDateTime)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function findFromDecadesAgo($key, $value, $ago, $endDateTime = null)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getDecadesAgo($ago, $endDateTime)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function findFromCenturiesAgo($key, $value, $ago, $endDateTime = null)
    {
        return $this->findWhereDateTimeBetween(
            $key, $value, 'created_at', DateTime::getCenturiesAgo($ago, $endDateTime)
        );
    }

    /**
     * @param $key
     * @param $value
     * @param $range
     * @param bool $exact
     *
     * @return mixed
     */
    public function findFromDateTimeRange($key, $value, $range, $exact = false)
    {
        if (!is_array($range)) {
            $range = DateTime::getDateTimeRange($range, $range, $exact);
        } else {
            $range = [
                (string) new Carbon($range[0]),
                (string) new Carbon($range[1]),
            ];
        }

        return $this->findWhereDateTimeBetween($key, $value, 'created_at', $range);
    }
}
