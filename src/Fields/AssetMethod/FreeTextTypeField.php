<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\Enums\FreeTextType;

trait FreeTextTypeField
{
    /**
     * Free text type field
     * Used by: AssetMethodFreeText
     *
     * @var FreeTextType|null
     */
    private $type;

    public function getType(): ?FreeTextType
    {
        return $this->type;
    }

    /**
     * @param FreeTextType|null $type
     * @return $this
     */
    public function setType(?FreeTextType $type): self
    {
        $this->type = $type;
        return $this;
    }
}
