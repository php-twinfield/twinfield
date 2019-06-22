<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

use PhpTwinfield\CostCenter;

/**
 * The cost center
 * Used by: CustomerLine, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dimension2Field
{
    /**
     * @var CostCenter|null
     */
    private $dimension2;

    public function getDimension2(): ?CostCenter
    {
        return $this->dimension2;
    }

    /**
     * @return $this
     */
    public function setDimension2(?CostCenter $dimension2): self
    {
        $this->dimension2 = $dimension2;
        return $this;
    }
}