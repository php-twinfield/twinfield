<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatRepTotalField
{
    /**
     * Vat rep total field
     * Used by: BankTransactionLine, CashTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $vatRepTotal;

    /**
     * @return Money|null
     */
    public function getVatRepTotal(): ?Money
    {
        return $this->vatRepTotal;
    }

    /**
     * @param Money|null $vatRepTotal
     * @return $this
     */
    public function setVatRepTotal(?Money $vatRepTotal)
    {
        $this->vatRepTotal = $vatRepTotal;

        return $this;
    }
}
