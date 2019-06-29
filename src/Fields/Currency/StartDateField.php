<?php

namespace PhpTwinfield\Fields\Currency;

/**
 * Start date field
 * Used by: CurrencyRate
 * @package PhpTwinfield\Traits
 */
trait StartDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $startDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeInterface|null $startDate
     * @return $this
     */
    public function setStartDate(?\DateTimeInterface $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }
}
