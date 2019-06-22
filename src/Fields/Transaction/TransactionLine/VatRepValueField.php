<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatRepValueField
{
    /**
     * VAT rep value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatRepValue;

    /**
     * @return Money|null
     */
    public function getVatRepValue(): ?Money
    {
        return $this->vatRepValue;
    }

    /**
     * @param Money|null $vatRepValue
     * @return $this
     */
    public function setVatRepValue(?Money $vatRepValue)
    {
        $this->vatRepValue = $vatRepValue;

        return $this;
    }
}