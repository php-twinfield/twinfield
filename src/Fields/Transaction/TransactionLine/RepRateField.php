<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

trait RepRateField
{
    /**
     * Rep rate field
     * Used by: BaseTransactionLine
     *
     * @var float|null
     */
    private $repRate;

    /**
     * @return null|float
     */
    public function getRepRate(): ?float
    {
        return $this->repRate;
    }

    /**
     * @param null|float $repRate
     * @return $this
     */
    public function setRepRate(?float $repRate): self
    {
        $this->repRate = $repRate;
        return $this;
    }
}