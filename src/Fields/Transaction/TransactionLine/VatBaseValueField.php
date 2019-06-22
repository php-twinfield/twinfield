<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatBaseValueField
{
    /**
     * Vat base value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatBaseValue;

    /**
     * @return Money|null
     */
    public function getVatBaseValue(): ?Money
    {
        return $this->vatBaseValue;
    }

    /**
     * @param Money|null $vatBaseValue
     * @return $this
     */
    public function setVatBaseValue(?Money $vatBaseValue)
    {
        $this->vatBaseValue = $vatBaseValue;

        return $this;
    }
}