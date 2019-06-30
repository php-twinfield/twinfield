<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait PayAvailableField
{
    /**
     * Pay available field
     * Used by: CustomerFinancials, SupplierFinancials
     *
     * @var bool
     */
    private $payAvailable;

    /**
     * @return bool
     */
    public function getPayAvailable(): ?bool
    {
        return $this->payAvailable;
    }

    /**
     * @param bool $payAvailable
     * @return $this
     */
    public function setPayAvailable(?bool $payAvailable): self
    {
        $this->payAvailable = $payAvailable;
        return $this;
    }
}
