<?php

namespace PhpTwinfield\Fields\Invoice;

trait FinancialNumberField
{
    /**
     * Financial number field
     * Used by: Invoice
     *
     * @var int|null
     */
    private $financialNumber;

    /**
     * @return null|int
     */
    public function getFinancialNumber(): ?int
    {
        return $this->financialNumber;
    }

    /**
     * @param null|int $financialNumber
     * @return $this
     */
    public function setFinancialNumber(?int $financialNumber): self
    {
        $this->financialNumber = $financialNumber;
        return $this;
    }
}
