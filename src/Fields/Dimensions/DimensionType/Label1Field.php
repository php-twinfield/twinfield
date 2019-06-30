<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionType;

trait Label1Field
{
    /**
     * Label 1 field
     * Used by: DimensionTypeAddress
     *
     * @var string|null
     */
    private $label1;

    /**
     * @return null|string
     */
    public function getLabel1(): ?string
    {
        return $this->label1;
    }

    /**
     * @param null|string $label1
     * @return $this
     */
    public function setLabel1(?string $label1): self
    {
        $this->label1 = $label1;
        return $this;
    }
}
