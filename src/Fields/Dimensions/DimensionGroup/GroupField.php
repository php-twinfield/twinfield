<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionGroup;

use PhpTwinfield\DimensionGroup;

/**
 * The dimension group
 * Used by: Customer, FixedAsset, GeneralLedger, Supplier
 *
 * @package PhpTwinfield\Traits
 */
trait GroupField
{
    /**
     * @var DimensionGroup|null
     */
    private $group;

    public function getGroup(): ?DimensionGroup
    {
        return $this->group;
    }

    /**
     * @return $this
     */
    public function setGroup(?DimensionGroup $group): self
    {
        $this->group = $group;
        return $this;
    }
}
