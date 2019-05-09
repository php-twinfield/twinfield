<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Enums\PerformanceType;

trait PerformanceTypeField
{
    /**
     * Performance type field
     * Used by: Article, InvoiceLine, InvoiceVatLine
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

    /**
     * @param string|null $performanceTypeString
     * @return $this
     * @throws Exception
     */
    public function setPerformanceTypeFromString(?string $performanceTypeString)
    {
        return $this->setPerformanceType(new PerformanceType((string)$performanceTypeString));
    }
}