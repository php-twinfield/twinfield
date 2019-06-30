<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: CustomerLine, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dimension1Field
{
    /**
     * @var GeneralLedger|null
     */
    private $dimension1;

    public function getDimension1(): ?GeneralLedger
    {
        return $this->dimension1;
    }

    /**
     * @return $this
     */
    public function setDimension1(?GeneralLedger $dimension1): self
    {
        $this->dimension1 = $dimension1;
        return $this;
    }
}
