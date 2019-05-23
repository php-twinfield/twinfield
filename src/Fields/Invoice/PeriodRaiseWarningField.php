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

    public function getPeriodRaiseWarningToString(): ?string
    {
        return ($this->getPeriodRaiseWarning()) ? 'true' : 'false';
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

    /**
     * @param string|null $periodRaiseWarningString
     * @return $this
     * @throws Exception
     */
    public function setPeriodRaiseWarningFromString(?string $periodRaiseWarningString)
    {
        return $this->setPeriodRaiseWarning(filter_var($periodRaiseWarningString, FILTER_VALIDATE_BOOLEAN));
    }
}