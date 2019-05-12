<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionType;

trait Label3Field
{
    /**
     * Label 3 field
     * Used by: DimensionTypeAddress
     *
     * @var string|null
     */
    private $label3;

    /**
     * @return null|string
     */
    public function getLabel3(): ?string
    {
        return $this->label3;
    }

    /**
     * @param null|string $label3
     * @return $this
     */
    public function setLabel3(?string $label3): self
    {
        $this->label3 = $label3;
        return $this;
    }
}