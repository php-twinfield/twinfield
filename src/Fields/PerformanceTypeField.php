<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Enums\PerformanceType;

trait PerformanceTypeField
{
    /**
     * Performance type field
     * Used by: Article, BankTransactionLine, CashTransactionLine, Invoice, InvoiceLine, InvoiceVatLine, JournalTransactionLine, SalesTransactionLine
     *
     * @var PerformanceType|null
     */
    private $performanceType;

    public function getPerformanceType(): ?PerformanceType
    {
        return $this->performanceType;
    }

    /**
     * @param PerformanceType|null $performanceType
     * @return $this
     */
    public function setPerformanceType(?PerformanceType $performanceType): self
    {
        $this->performanceType = $performanceType;
        return $this;
    }
}
