<?php namespace AwatBayazidi\Abzar\Traits\Repositories;

use Carbon\Carbon;
use AwatBayazidi\Foundation\Support\DateTime;

/**
 * Class DateTimeTrait.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
trait DateTimeTrait
{
    use DateTimeFindTrait;

    /**
     * @return mixed
     */
    public function fromToday()
    {
        return $this->fromThisDay();
    }

    /**
     * @return mixed
     */
    public function fromYesterday()
    {
        return $this->fromLastDay();
    }

    /**
     * @return mixed
     */
    public function fromLastSevenDays()
    {
        return $this->fromDaysAgo(6, Carbon::now()->endOfDay());
    }

    /**
     * @return mixed
     */
    public function fromLastThirtyDays()
    {
        return $this->fromDaysAgo(29, Carbon::now()->endOfDay());
    }

    /**
     * @return mixed
     */
    public function fromLastFourWeeks()
    {
        return $this->fromDaysAgo(Carbon::now()->daysInMonth, Carbon::now()->endOfDay());
    }

    /**
     * @return mixed
     */
    public function fromThisDay()
    {
        return $this->fromDaysAgo(0);
    }

    /**
     * @return mixed
     */
    public function fromThisWeek()
    {
        return $this->fromWeeksAgo(0);
    }

    /**
     * @return mixed
     */
    public function fromThisMonth()
    {
        return $this->fromMonthsAgo(0);
    }

    /**
     * @return mixed
     */
    public function fromThisYear()
    {
        return $this->fromYearsAgo(0);
    }

    /**
     * @return mixed
     */
    public function fromThisDecade()
    {
        return $this->fromDecadesAgo(0);
    }

    /**
     * @return mixed
     */
    public function fromThisCentury()
    {
        return $this->fromCenturiesAgo(0);
    }

    /**
     * @return mixed
     */
    public function fromLastDay()
    {
        return $this->fromDaysAgo(1);
    }

    /**
     * @return mixed
     */
    public function fromLastWeek()
    {
        return $this->fromWeeksAgo(1);
    }

    /**
     * @return mixed
     */
    public function fromLastMonth()
    {
        return $this->fromMonthsAgo(1);
    }

    /**
     * @return mixed
     */
    public function fromLastYear()
    {
        return $this->fromYearsAgo(1);
    }

    /**
     * @return mixed
     */
    public function fromLastDecade()
    {
        return $this->fromDecadesAgo(1);
    }

    /**
     * @return mixed
     */
    public function fromLastCentury()
    {
        return $this->fromCenturiesAgo(1);
    }

    /**
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function fromDaysAgo($ago, $endDateTime = null)
    {
        return $this->fromDateTimeRange(DateTime::getDaysAgo($ago, $endDateTime));
    }

    /**
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function fromWeeksAgo($ago, $endDateTime = null)
    {
        return $this->fromDateTimeRange(DateTime::getWeeksAgo($ago, $endDateTime));
    }

    /**
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function fromMonthsAgo($ago, $endDateTime = null)
    {
        return $this->fromDateTimeRange(DateTime::getMonthsAgo($ago, $endDateTime));
    }

    /**
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function fromYearsAgo($ago, $endDateTime = null)
    {
        return $this->fromDateTimeRange(DateTime::getYearsAgo($ago, $endDateTime));
    }

    /**
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function fromDecadesAgo($ago, $endDateTime = null)
    {
        return $this->fromDateTimeRange(DateTime::getDecadesAgo($ago, $endDateTime));
    }

    /**
     * @param $ago
     * @param null $endDateTime
     *
     * @return mixed
     */
    public function fromCenturiesAgo($ago, $endDateTime = null)
    {
        return $this->fromDateTimeRange(DateTime::getCenturiesAgo($ago, $endDateTime));
    }

    /**
     * @param $range
     * @param bool $exact
     *
     * @return mixed
     */
    public function fromDateTimeRange($range, $exact = false)
    {
        if (!is_array($range)) {
            $range = DateTime::getDateTimeRange($range, $range, $exact);
        } else {
            $range = [
                (string) new Carbon($range[0]),
                (string) new Carbon($range[1]),
            ];
        }
        $this->setQuery($this->getQuery()->whereBetween('created_at', $range));
        return $this;

    }
}
