<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait DueDaysField
{
    /**
     * Due days field
     * Used by: CustomerFinancials, SupplierFinancials
     *
     * @var int|null
     */
    private $dueDays;

    /**
     * @return null|int
     */
    public function getDueDays(): ?int
    {
        return $this->dueDays;
    }

    /**
     * @param null|int $dueDays
     * @return $this
     */
    public function setDueDays(?int $dueDays): self
    {
        $this->dueDays = $dueDays;
        return $this;
    }
}