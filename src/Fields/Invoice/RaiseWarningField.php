<?php

namespace PhpTwinfield\Fields\Invoice;

trait RaiseWarningField
{
    /**
     * Raise warning field
     * Used by: Invoice
     *
     * @var bool
     */
    private $raiseWarning;

    /**
     * @return bool
     */
    public function getRaiseWarning(): ?bool
    {
        return $this->raiseWarning;
    }

    /**
     * @param bool $raiseWarning
     * @return $this
     */
    public function setRaiseWarning(?bool $raiseWarning): self
    {
        $this->raiseWarning = $raiseWarning;
        return $this;
    }
}