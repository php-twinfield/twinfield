<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait Dimension2IDField
{
    /**
     * Dimension 2 ID field
     * Used by: CustomerLine, SupplierLine
     *
     * @var string|null
     */
    private $dimension2ID;

    /**
     * @return null|string
     */
    public function getDimension2ID(): ?string
    {
        return $this->dimension2ID;
    }

    /**
     * @param null|string $dimension2ID
     * @return $this
     */
    public function setDimension2ID(?string $dimension2ID): self
    {
        $this->dimension2ID = $dimension2ID;
        return $this;
    }
}