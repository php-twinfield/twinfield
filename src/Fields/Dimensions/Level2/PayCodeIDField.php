<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait PayCodeIDField
{
    /**
     * Pay code ID field
     * Used by: CustomerFinancials, SupplierFinancials
     *
     * @var string|null
     */
    private $payCodeID;

    /**
     * @return null|string
     */
    public function getPayCodeID(): ?string
    {
        return $this->payCodeID;
    }

    /**
     * @param null|string $payCodeID
     * @return $this
     */
    public function setPayCodeID(?string $payCodeID): self
    {
        $this->payCodeID = $payCodeID;
        return $this;
    }
}
