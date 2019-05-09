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

    public function getDepreciationGroupToCode(): ?string
    {
        if ($this->getDepreciationGroup() != null) {
            return $this->depreciationGroup->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDepreciationGroup(?DimensionGroup $depreciationGroup): self
    {
        $this->depreciationGroup = $depreciationGroup;
        return $this;
    }

    /**
     * @param string|null $depreciationGroupCode
     * @return $this
     * @throws Exception
     */
    public function setDepreciationGroupFromCode(?string $depreciationGroupCode)
    {
        $depreciationGroup = new DimensionGroup();
        $depreciationGroup->setCode($depreciationGroupCode);
        return $this->setDepreciationGroup($depreciationGroup);
    }
}