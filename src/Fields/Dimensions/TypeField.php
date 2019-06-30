<?php

namespace PhpTwinfield\Fields\Dimensions;

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
}
