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

    public function getRaiseWarningToString(): ?string
    {
        return ($this->getRaiseWarning()) ? 'true' : 'false';
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

    /**
     * @param string|null $raiseWarningString
     * @return $this
     * @throws Exception
     */
    public function setRaiseWarningFromString(?string $raiseWarningString)
    {
        return $this->setRaiseWarning(filter_var($raiseWarningString, FILTER_VALIDATE_BOOLEAN));
    }
}