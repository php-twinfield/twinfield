<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait IbanField
{
    /**
     * IBAN field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $iban;

    /**
     * @return null|string
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param null|string $iban
     * @return $this
     */
    public function setIban(?string $iban): self
    {
        $this->iban = $iban;
        return $this;
    }
}