<?php

namespace PhpTwinfield\Fields\Rate;

/**
 * End date field
 * Used by: RateRateChange
 *
 * @package PhpTwinfield\Traits
 */
trait EndDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $endDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface|null $endDate
     * @return $this
     */
    public function setEndDate(?\DateTimeInterface $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }
}
