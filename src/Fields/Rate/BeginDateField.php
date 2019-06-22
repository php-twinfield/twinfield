<?php

namespace PhpTwinfield\Fields\Rate;


/**
 * Begin date field
 * Used by: RateRateChange
 *
 * @package PhpTwinfield\Traits
 */
trait BeginDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $beginDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    /**
     * @param \DateTimeInterface|null $beginDate
     * @return $this
     */
    public function setBeginDate(?\DateTimeInterface $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }
}