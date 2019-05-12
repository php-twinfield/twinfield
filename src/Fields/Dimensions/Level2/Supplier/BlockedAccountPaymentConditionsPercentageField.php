<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Supplier;

trait BlockedAccountPaymentConditionsPercentageField
{
    /**
     * Blocked account payment conditions percentage field
     * Used by: Supplier
     *
     * @var float|null
     */
    private $blockedAccountPaymentConditionsPercentage;

    /**
     * @return null|float
     */
    public function getBlockedAccountPaymentConditionsPercentage(): ?float
    {
        return $this->blockedAccountPaymentConditionsPercentage;
    }

    /**
     * @param null|float $blockedAccountPaymentConditionsPercentage
     * @return $this
     */
    public function setBlockedAccountPaymentConditionsPercentage(?float $blockedAccountPaymentConditionsPercentage): self
    {
        $this->blockedAccountPaymentConditionsPercentage = $blockedAccountPaymentConditionsPercentage;
        return $this;
    }
}