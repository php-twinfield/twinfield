<?php

namespace PhpTwinfield\Fields\VatCode;

use PhpTwinfield\Enums\VatType;

trait TypeField
{
    /**
     * VAT code type field
     * Used by: VatCode
     *
     * @var VatType|null
     */
    private $type;

    public function getType(): ?VatType
    {
        return $this->type;
    }

    /**
     * @param VatType|null $type
     * @return $this
     */
    public function setType(?VatType $type): self
    {
        $this->type = $type;
        return $this;
    }
}