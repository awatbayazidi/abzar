<?php namespace AwatBayazidi\Abzar\Traits\Repositories;

use AwatBayazidi\Foundation\Model\Repository\Criteria\Criterion;


trait CriteriaTrait
{
    /**
     * @var
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);

        return $this;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criterion $criterion
     *
     * @return $this
     */
    public function getByCriteria(Criterion $criterion)
    {
        $model = $criterion->apply($this->getModel(), $this);

        $this->setModel($model);

        return $this;
    }

    /**
     * @param Criterion $criterion
     *
     * @return $this
     */
    public function pushCriteria(Criterion $criterion)
    {
        $this->criteria->push($criterion);

        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }
        foreach ($this->getCriteria() as $criterion) {
            if ($criterion instanceof Criterion) {
                $model = $criterion->apply($this->getModel(), $this);
                $this->setModel($model);
            }
        }

        $this->setModel($this->getModel());

        return $this;
    }
}
