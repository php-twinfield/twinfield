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

    /**
     * @param string|null $dateRaiseWarningString
     * @return $this
     * @throws Exception
     */
    public function setDateRaiseWarningFromString(?string $dateRaiseWarningString)
    {
        return $this->setDateRaiseWarning(filter_var($dateRaiseWarningString, FILTER_VALIDATE_BOOLEAN));
    }
}