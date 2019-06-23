<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Supplier;

use PhpTwinfield\Enums\BlockedAccountPaymentConditionsIncludeVat;

trait BlockedAccountPaymentConditionsIncludeVatField
{
    /**
     * Blocked account payment conditions include vat field
     * Used by: Supplier
     *
     * @var BlockedAccountPaymentConditionsIncludeVat|null
     */
    private $blockedAccountPaymentConditionsIncludeVat;

    public function getBlockedAccountPaymentConditionsIncludeVat(): ?BlockedAccountPaymentConditionsIncludeVat
    {
        return $this->blockedAccountPaymentConditionsIncludeVat;
    }

    /**
     * @param BlockedAccountPaymentConditionsIncludeVat|null $blockedAccountPaymentConditionsIncludeVat
     * @return $this
     */
    public function setBlockedAccountPaymentConditionsIncludeVat(?BlockedAccountPaymentConditionsIncludeVat $blockedAccountPaymentConditionsIncludeVat): self
    {
        $this->blockedAccountPaymentConditionsIncludeVat = $blockedAccountPaymentConditionsIncludeVat;
        return $this;
    }
}