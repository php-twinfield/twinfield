<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait RepValueField
{
    /**
     * Rep value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $repValue;

    /**
     * @return Money|null
     */
    public function getRepValue(): ?Money
    {
        return $this->repValue;
    }

    /**
     * @param Money|null $repValue
     * @return $this
     */
    public function setRepValue(?Money $repValue)
    {
        $this->repValue = $repValue;

        return $this;
    }
}
