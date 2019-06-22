<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatBaseTotalField
{
    /**
     * Vat base total field
     * Used by: BankTransactionLine, CashTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $vatBaseTotal;

    /**
     * @return Money|null
     */
    public function getVatBaseTotal(): ?Money
    {
        return $this->vatBaseTotal;
    }

    /**
     * @param Money|null $vatBaseTotal
     * @return $this
     */
    public function setVatBaseTotal(?Money $vatBaseTotal)
    {
        $this->vatBaseTotal = $vatBaseTotal;

        return $this;
    }
}