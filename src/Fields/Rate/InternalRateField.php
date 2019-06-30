<?php

namespace PhpTwinfield\Fields\Rate;

trait InternalRateField
{
    /**
     * Internal rate field
     * Used by: RateRateChange
     *
     * @var float|null
     */
    private $internalRate;

    /**
     * @return null|float
     */
    public function getInternalRate(): ?float
    {
        return $this->internalRate;
    }

    /**
     * @param null|float $internalRate
     * @return $this
     */
    public function setInternalRate(?float $internalRate): self
    {
        $this->internalRate = $internalRate;
        return $this;
    }
}
