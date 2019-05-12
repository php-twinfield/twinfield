<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionType;

trait Label2Field
{
    /**
     * Label 2 field
     * Used by: DimensionTypeAddress
     *
     * @var string|null
     */
    private $label2;

    /**
     * @return null|string
     */
    public function getLabel2(): ?string
    {
        return $this->label2;
    }

    /**
     * @param null|string $label2
     * @return $this
     */
    public function setLabel2(?string $label2): self
    {
        $this->label2 = $label2;
        return $this;
    }
}