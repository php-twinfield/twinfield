<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Exception;

/**
 * Performance date field
 * Used by: BankTransactionLine, CashTransactionLine, Invoice, InvoiceLine, InvoiceVatLine, JournalTransactionLine, SalesTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait PerformanceDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $performanceDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getPerformanceDate(): ?\DateTimeInterface
    {
        return $this->performanceDate;
    }

    /**
     * @param \DateTimeInterface|null $performanceDate
     * @return $this
     */
    public function setPerformanceDate(?\DateTimeInterface $performanceDate)
    {
        $this->performanceDate = $performanceDate;
        return $this;
    }
}