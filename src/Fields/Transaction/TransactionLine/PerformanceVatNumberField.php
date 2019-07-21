<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

trait PerformanceVatNumberField
{
    /**
     * Performance vat number field
     * Used by: BankTransactionLine, CashTransactionLine, JournalTransactionLine, SalesTransactionLine
     *
     * @var string|null
     */
    private $performanceVatNumber;

    /**
     * @return null|string
     */
    public function getPerformanceVatNumber(): ?string
    {
        return $this->performanceVatNumber;
    }

    /**
     * @param null|string $performanceVatNumber
     * @return $this
     */
    public function setPerformanceVatNumber(?string $performanceVatNumber): self
    {
        $this->performanceVatNumber = $performanceVatNumber;
        return $this;
    }
}
