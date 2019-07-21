<?php

namespace PhpTwinfield\Fields\Dimensions;

/**
 * The dimension
 * Used by: CustomerFinancials, FixedAssetFinancials, SupplierFinancials
 *
 * @package PhpTwinfield\Traits
 */
trait SubstituteWithField
{
    /**
     * @var object|null
     */
    private $substituteWith;

    public function getSubstituteWith()
    {
        return $this->substituteWith;
    }

    /**
     * @return $this
     */
    public function setSubstituteWith($substituteWith): self
    {
        $this->substituteWith = $substituteWith;
        return $this;
    }
}
