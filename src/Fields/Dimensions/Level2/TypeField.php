<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

use PhpTwinfield\Enums\AddressType;

trait TypeField
{
    /**
     * Type field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var AddressType|null
     */
    private $type;

    public function getType(): ?AddressType
    {
        return $this->type;
    }

    /**
     * @param AddressType|null $type
     * @return $this
     */
    public function setType(?AddressType $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string|null $typeString
     * @return $this
     * @throws Exception
     */
    public function setTypeFromString(?string $typeString)
    {
        return $this->setType(new AddressType((string)$typeString));
    }
}