<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatTotalField
{
    /**
     * Vat total field
     * Used by: BankTransactionLine, CashTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $vatTotal;

    /**
     * @return Money|null
     */
    public function getVatTotal(): ?Money
    {
        return $this->vatTotal;
    }

    /**
     * @param Money|null $vatTotal
     * @return $this
     */
    public function setVatTotal(?Money $vatTotal)
    {
        $this->vatTotal = $vatTotal;

        return $this;
    }
}
