<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionType;

trait Label5Field
{
    /**
     * Label 5 field
     * Used by: DimensionTypeAddress
     *
     * @var string|null
     */
    private $label5;

    /**
     * @return null|string
     */
    public function getLabel5(): ?string
    {
        return $this->label5;
    }

    /**
     * @param null|string $label5
     * @return $this
     */
    public function setLabel5(?string $label5): self
    {
        $this->label5 = $label5;
        return $this;
    }
}