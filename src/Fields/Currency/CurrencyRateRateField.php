<?php

namespace PhpTwinfield\Fields\Currency;

trait CurrencyRateRateField
{
    /**
     * Currency rate rate field
     * Used by: CurrencyRate
     *
     * @var float|null
     */
    private $rate;

    /**
     * @return null|float
     */
    public function getRate(): ?float
    {
        return $this->rate;
    }

    /**
     * @param null|float $rate
     * @return $this
     */
    public function setRate(?float $rate): self
    {
        $this->rate = $rate;
        return $this;
    }
}