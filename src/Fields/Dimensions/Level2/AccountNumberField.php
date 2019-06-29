<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait AccountNumberField
{
    /**
     * Account number field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $accountNumber;

    /**
     * @return null|string
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    /**
     * @param null|string $accountNumber
     * @return $this
     */
    public function setAccountNumber(?string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }
}
