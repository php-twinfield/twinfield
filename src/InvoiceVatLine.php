<?php
namespace PhpTwinfield;

use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Transactions\TransactionLineFields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceVatLine
{
    use VatCodeField;

    private $vatValue;
    private $performanceDate;

    /**
     * @var PerformanceType|null Mandatory in case of an ICT VAT code.
     */
    private $performanceType;

    public function getVatValue()
    {
        return $this->vatValue;
    }

    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;
        return $this;
    }

    public function getPerformanceDate()
    {
        return $this->performanceDate;
    }

    public function setPerformanceDate($performanceDate)
    {
        $this->performanceDate = $performanceDate;
        return $this;
    }

    public function getPerformanceType(): ?PerformanceType
    {
        return $this->performanceType;
    }

    public function setPerformanceType(?PerformanceType $performanceType): self
    {
        $this->performanceType = $performanceType;
        return $this;
    }
}
