<?php

namespace PhpTwinfield\Fields\Rate;

trait ExternalRateField
{
    /**
     * External rate field
     * Used by: RateRateChange
     *
     * @var float|null
     */
    private $externalRate;

    /**
     * @return null|float
     */
    public function getExternalRate(): ?float
    {
        return $this->externalRate;
    }

    /**
     * @param null|float $externalRate
     * @return $this
     */
    public function setExternalRate(?float $externalRate): self
    {
        $this->externalRate = $externalRate;
        return $this;
    }
}