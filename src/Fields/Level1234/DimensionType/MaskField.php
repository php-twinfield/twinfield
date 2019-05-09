<?php

namespace PhpTwinfield\Fields\Level1234\DimensionType;

trait MaskField
{
    /**
     * Mask field
     * Used by: DimensionType
     *
     * @var string|null
     */
    private $mask;

    /**
     * @return null|string
     */
    public function getMask(): ?string
    {
        return $this->mask;
    }

    /**
     * @param null|string $mask
     * @return $this
     */
    public function setMask(?string $mask): self
    {
        $this->mask = $mask;
        return $this;
    }
}