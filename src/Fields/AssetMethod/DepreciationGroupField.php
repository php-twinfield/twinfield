<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\DimensionGroup;

/**
 * The dimension group
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait DepreciationGroupField
{
    /**
     * @var DimensionGroup|null
     */
    private $depreciationGroup;

    public function getDepreciationGroup(): ?DimensionGroup
    {
        return $this->depreciationGroup;
    }

    /**
     * @return $this
     */
    public function setDepreciationGroup(?DimensionGroup $depreciationGroup): self
    {
        $this->depreciationGroup = $depreciationGroup;
        return $this;
    }
}
