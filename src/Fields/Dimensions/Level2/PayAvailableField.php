<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait PayAvailableField
{
    /**
     * Pay available field
     * Used by: CustomerFinancials, SupplierFinancials
     *
     * @var bool
     */
    private $payAvailable;

    /**
     * @return bool
     */
    public function getPayAvailable(): ?bool
    {
        return $this->payAvailable;
    }

    public function getPayAvailableToString(): ?string
    {
        return ($this->getPayAvailable()) ? 'true' : 'false';
    }

    /**
     * @param bool $payAvailable
     * @return $this
     */
    public function setPayAvailable(?bool $payAvailable): self
    {
        $this->payAvailable = $payAvailable;
        return $this;
    }

    /**
     * @param string|null $payAvailableString
     * @return $this
     * @throws Exception
     */
    public function setPayAvailableFromString(?string $payAvailableString)
    {
        return $this->setPayAvailable(filter_var($payAvailableString, FILTER_VALIDATE_BOOLEAN));
    }
}