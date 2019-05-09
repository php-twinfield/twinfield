<?php

namespace PhpTwinfield\Fields;

trait ElementValueField
{
    /**
     * Element value field
     * Used by: AssetMethodFreeText, CustomerChildValidation, GeneralLedgerChildValidation, SupplierChildValidation
     *
     * @var string|null
     */
    private $value;

    /**
     * @return null|string
     */
    public function getElementValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param null|string $value
     * @return $this
     */
    public function setElementValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }
}