<?php

namespace PhpTwinfield\Fields\Invoice;

trait FinancialCodeField
{
    /**
     * Financial code field
     * Used by: Invoice
     *
     * @var string|null
     */
    private $financialCode;

    /**
     * @return null|string
     */
    public function getFinancialCode(): ?string
    {
        return $this->financialCode;
    }

    /**
     * @param null|string $financialCode
     * @return $this
     */
    public function setFinancialCode(?string $financialCode): self
    {
        $this->financialCode = $financialCode;
        return $this;
    }
}
