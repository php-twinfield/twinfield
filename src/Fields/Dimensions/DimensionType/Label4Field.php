<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionType;

trait Label4Field
{
    /**
     * Label 4 field
     * Used by: DimensionTypeAddress
     *
     * @var string|null
     */
    private $label4;

    /**
     * @return null|string
     */
    public function getLabel4(): ?string
    {
        return $this->label4;
    }

    /**
     * @param null|string $label4
     * @return $this
     */
    public function setLabel4(?string $label4): self
    {
        $this->label4 = $label4;
        return $this;
    }
}
