<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

/**
 * First run date field
 * Used by: CustomerCollectMandate
 *
 * @package PhpTwinfield\Traits
 */
trait FirstRunDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $firstRunDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getFirstRunDate(): ?\DateTimeInterface
    {
        return $this->firstRunDate;
    }

    /**
     * @param \DateTimeInterface|null $firstRunDate
     * @return $this
     */
    public function setFirstRunDate(?\DateTimeInterface $firstRunDate)
    {
        $this->firstRunDate = $firstRunDate;
        return $this;
    }
}
