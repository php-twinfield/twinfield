<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait BaseValueField
{
    /**
     * Base value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $baseValue;

    /**
     * @return Money|null
     */
    public function getBaseValue(): ?Money
    {
        return $this->baseValue;
    }

    /**
     * @param Money|null $baseValue
     * @return $this
     */
    public function setBaseValue(?Money $baseValue)
    {
        $this->baseValue = $baseValue;

        return $this;
    }
}
