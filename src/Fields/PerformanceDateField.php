<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Performance date field
 * Used by: BankTransactionLine, CashTransactionLine, Invoice, InvoiceLine, InvoiceVatLine, JournalTransactionLine, SalesTransactionLine
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
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
     * @return string|null
     */
    public function getPerformanceDateToString(): ?string
    {
        if ($this->getPerformanceDate() != null) {
            return Util::formatDate($this->getPerformanceDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $performanceDateString
     * @return $this
     * @throws Exception
     */
    public function setPerformanceDateFromString(?string $performanceDateString)
    {
        if ((bool)strtotime($performanceDateString)) {
            return $this->setPerformanceDate(Util::parseDate($performanceDateString));
        } else {
            return $this->setPerformanceDate(null);
        }
    }
}