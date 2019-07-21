<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait RepValueOpenField
{
    /**
     * Rep value open field
     * Used by: RepTransactionLine
     *
     * @var Money|null
     */
    private $repValueOpen;

    /**
     * @return Money|null
     */
    public function getRepValueOpen(): ?Money
    {
        return $this->repValueOpen;
    }

    /**
     * @param Money|null $repValueOpen
     * @return $this
     */
    public function setRepValueOpen(?Money $repValueOpen)
    {
        $this->repValueOpen = $repValueOpen;

        return $this;
    }
}
