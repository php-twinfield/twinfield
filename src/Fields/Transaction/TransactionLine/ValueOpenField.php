<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait ValueOpenField
{
    /**
     * Value open field
     * Used by: PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $valueOpen;

    /**
     * @return Money|null
     */
    public function getValueOpen(): ?Money
    {
        return $this->valueOpen;
    }

    /**
     * @param Money|null $valueOpen
     * @return $this
     */
    public function setValueOpen(?Money $valueOpen)
    {
        $this->valueOpen = $valueOpen;

        return $this;
    }
}