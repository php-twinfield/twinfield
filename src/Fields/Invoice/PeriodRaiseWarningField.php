<?php

namespace PhpTwinfield\Fields\Invoice;

trait PeriodRaiseWarningField
{
    /**
     * Period raise warning field
     * Used by: Invoice
     *
     * @var bool
     */
    private $periodRaiseWarning;

    /**
     * @return bool
     */
    public function getPeriodRaiseWarning(): ?bool
    {
        return $this->periodRaiseWarning;
    }

    /**
     * @param bool $periodRaiseWarning
     * @return $this
     */
    public function setPeriodRaiseWarning(?bool $periodRaiseWarning): self
    {
        $this->periodRaiseWarning = $periodRaiseWarning;
        return $this;
    }
}
