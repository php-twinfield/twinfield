<?php

namespace PhpTwinfield\Fields\Level1234;

use PhpTwinfield\Enums\ChildValidationType;

trait TypeField
{
    /**
     * Type field
     * Used by: CustomerChildValidation, GeneralLedgerChildValidation, SupplierChildValidation
     *
     * @var ChildValidationType|null
     */
    private $type;

    public function getType(): ?ChildValidationType
    {
        return $this->type;
    }

    /**
     * @param ChildValidationType|null $type
     * @return $this
     */
    public function setType(?ChildValidationType $type): self
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
        return $this->setType(new ChildValidationType((string)$typeString));
    }
}