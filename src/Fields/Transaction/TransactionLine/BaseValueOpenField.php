<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait BaseValueOpenField
{
    /**
     * Base value open field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $baseValueOpen;

    /**
     * @return Money|null
     */
    public function getBaseValueOpen(): ?Money
    {
        return $this->baseValueOpen;
    }
    
    /**
     * @param Money|null $baseValueOpen
     * @return $this
     */
    public function setBaseValueOpen(?Money $baseValueOpen)
    {
        $this->baseValueOpen = $baseValueOpen;

        return $this;
    }
}