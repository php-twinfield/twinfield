<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait BankNameField
{
    /**
     * Bank name field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $bankName;

    /**
     * @return null|string
     */
    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    /**
     * @param null|string $bankName
     * @return $this
     */
    public function setBankName(?string $bankName): self
    {
        $this->bankName = $bankName;
        return $this;
    }
}