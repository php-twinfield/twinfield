<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

/**
 * The dimension
 * Used by: CustomerLine, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dimension3Field
{
    /**
     * @var object|null
     */
    private $dimension3;

    public function getDimension3()
    {
        return $this->dimension3;
    }

    /**
     * @return $this
     */
    public function setDimension3($dimension3): self
    {
        $this->dimension3 = $dimension3;
        return $this;
    }
}