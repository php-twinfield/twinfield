<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait EBillingField
{
    /**
     * E-Billing field
     * Used by: CustomerFinancials
     *
     * @var bool
     */
    private $eBilling;

    /**
     * @return bool
     */
    public function getEBilling(): ?bool
    {
        return $this->eBilling;
    }

    public function getEBillingToString(): ?string
    {
        return ($this->getEBilling()) ? 'true' : 'false';
    }

    /**
     * @param bool $eBilling
     * @return $this
     */
    public function setEBilling(?bool $eBilling): self
    {
        $this->eBilling = $eBilling;
        return $this;
    }

    /**
     * @param string|null $eBillingString
     * @return $this
     * @throws Exception
     */
    public function setEBillingFromString(?string $eBillingString)
    {
        return $this->setEBilling(filter_var($eBillingString, FILTER_VALIDATE_BOOLEAN));
    }
}