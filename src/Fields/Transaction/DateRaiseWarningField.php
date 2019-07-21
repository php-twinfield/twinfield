<?php

namespace PhpTwinfield\Fields\Transaction;

trait DateRaiseWarningField
{
    /**
     * Date raise warning field
     * Used by: BaseTransaction
     *
     * @var bool
     */
    private $dateRaiseWarning;

    /**
     * @return bool
     */
    public function getDateRaiseWarning(): ?bool
    {
        return $this->dateRaiseWarning;
    }

    public function getDateRaiseWarningToString(): ?string
    {
        return ($this->getDateRaiseWarning()) ? 'true' : 'false';
    }

    /**
     * @param bool $dateRaiseWarning
     * @return $this
     */
    public function setDateRaiseWarning(?bool $dateRaiseWarning): self
    {
        $this->dateRaiseWarning = $dateRaiseWarning;
        return $this;
    }
}
