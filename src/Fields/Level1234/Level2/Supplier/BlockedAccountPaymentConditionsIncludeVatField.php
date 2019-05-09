<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Supplier;

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

    /**
     * @param string|null $blockedAccountPaymentConditionsIncludeVatString
     * @return $this
     * @throws Exception
     */
    public function setBlockedAccountPaymentConditionsIncludeVatFromString(?string $blockedAccountPaymentConditionsIncludeVatString)
    {
        return $this->setBlockedAccountPaymentConditionsIncludeVat(new BlockedAccountPaymentConditionsIncludeVat((string)$blockedAccountPaymentConditionsIncludeVatString));
    }
}