<?php

namespace PhpTwinfield\Fields\Dimensions;

trait EndPeriodField
{
    /**
     * General ledger end period field
     * Used by: Customer, GeneralLedger, Supplier
     *
     * @var int|null
     */
    private $endPeriod;

    /**
     * @return null|int
     */
    public function getEndPeriod(): ?int
    {
        return $this->endPeriod;
    }

    /**
     * @param null|int $endPeriod
     * @return $this
     */
    public function setEndPeriod(?int $endPeriod): self
    {
        $this->endPeriod = $endPeriod;
        return $this;
    }
}
