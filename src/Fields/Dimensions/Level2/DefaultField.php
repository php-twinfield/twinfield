<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait DefaultField
{
    /**
     * Default field
     * Used by: CustomerAddress, CustomerBank, SupplierAddress, SupplierBank
     *
     * @var bool
     */
    private $default;

    /**
     * @return bool
     */
    public function getDefault(): ?bool
    {
        return $this->default;
    }

    public function getDefaultToString(): ?string
    {
        return ($this->getDefault()) ? 'true' : 'false';
    }

    /**
     * @param bool $default
     * @return $this
     */
    public function setDefault(?bool $default): self
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @param string|null $defaultString
     * @return $this
     * @throws Exception
     */
    public function setDefaultFromString(?string $defaultString)
    {
        return $this->setDefault(filter_var($defaultString, FILTER_VALIDATE_BOOLEAN));
    }
}