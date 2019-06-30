<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait Dimension1IDField
{
    /**
     * Dimension 1 ID field
     * Used by: CustomerLine, SupplierLine
     *
     * @var string|null
     */
    private $dimension1ID;

    /**
     * @return null|string
     */
    public function getDimension1ID(): ?string
    {
        return $this->dimension1ID;
    }

    /**
     * @param null|string $dimension1ID
     * @return $this
     */
    public function setDimension1ID(?string $dimension1ID): self
    {
        $this->dimension1ID = $dimension1ID;
        return $this;
    }
}
