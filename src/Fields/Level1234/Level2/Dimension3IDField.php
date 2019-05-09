<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait Dimension3IDField
{
    /**
     * Dimension 3 ID field
     * Used by: CustomerLine, SupplierLine
     *
     * @var string|null
     */
    private $dimension3ID;

    /**
     * @return null|string
     */
    public function getDimension3ID(): ?string
    {
        return $this->dimension3ID;
    }

    /**
     * @param null|string $dimension3ID
     * @return $this
     */
    public function setDimension3ID(?string $dimension3ID): self
    {
        $this->dimension3ID = $dimension3ID;
        return $this;
    }
}