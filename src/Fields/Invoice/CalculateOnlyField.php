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

    public function getCalculateOnlyToString(): ?string
    {
        return ($this->getCalculateOnly()) ? 'true' : 'false';
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

    /**
     * @param string|null $calculateOnlyString
     * @return $this
     * @throws Exception
     */
    public function setCalculateOnlyFromString(?string $calculateOnlyString)
    {
        return $this->setCalculateOnly(filter_var($calculateOnlyString, FILTER_VALIDATE_BOOLEAN));
    }
}