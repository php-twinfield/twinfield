<?php

namespace PhpTwinfield\Fields\Level1234\DimensionType;

trait Label6Field
{
    /**
     * Label 6 field
     * Used by: DimensionTypeAddress
     *
     * @var string|null
     */
    private $label6;

    /**
     * @return null|string
     */
    public function getLabel6(): ?string
    {
        return $this->label6;
    }

    /**
     * @param null|string $label6
     * @return $this
     */
    public function setLabel6(?string $label6): self
    {
        $this->label6 = $label6;
        return $this;
    }
}