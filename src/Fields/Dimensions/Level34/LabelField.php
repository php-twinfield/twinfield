<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait LabelField
{
    /**
     * Label field
     * Used by: ActivityQuantity, ProjectQuantity
     *
     * @var string|null
     */
    private $label;

    /**
     * @return null|string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param null|string $label
     * @return $this
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }
}
