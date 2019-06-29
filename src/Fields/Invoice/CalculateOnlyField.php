<?php

namespace PhpTwinfield\Fields\Invoice;

trait CalculateOnlyField
{
    /**
     * Calculate only field
     * Used by: Invoice
     *
     * @var bool
     */
    private $calculateOnly;

    /**
     * @return bool
     */
    public function getCalculateOnly(): ?bool
    {
        return $this->calculateOnly;
    }

    /**
     * @param bool $calculateOnly
     * @return $this
     */
    public function setCalculateOnly(?bool $calculateOnly): self
    {
        $this->calculateOnly = $calculateOnly;
        return $this;
    }
}
