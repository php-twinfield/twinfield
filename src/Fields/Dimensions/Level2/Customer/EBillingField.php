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

    /**
     * @param bool $eBilling
     * @return $this
     */
    public function setEBilling(?bool $eBilling): self
    {
        $this->eBilling = $eBilling;
        return $this;
    }
}