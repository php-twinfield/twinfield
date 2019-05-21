<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

trait RateField
{
    /**
     * Rate field
     * Used by: BaseTransactionLine
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