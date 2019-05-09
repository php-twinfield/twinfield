<?php

namespace PhpTwinfield\Fields\Level1234;

use PhpTwinfield\DimensionGroup;

/**
 * The dimension group
 * Used by: Customer, FixedAsset, GeneralLedger, Supplier
 *
 * @package PhpTwinfield\Traits
 */
trait DimensionGroupField
{
    /**
     * @var DimensionGroup|null
     */
    private $group;

    public function getGroup(): ?DimensionGroup
    {
        return $this->group;
    }

    public function getGroupToCode(): ?string
    {
        if ($this->getGroup() != null) {
            return $this->group->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setGroup(?DimensionGroup $group): self
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @param string|null $groupCode
     * @return $this
     * @throws Exception
     */
    public function setGroupFromCode(?string $groupCode)
    {
        $group = new DimensionGroup();
        $group->setCode($groupCode);
        return $this->setGroup($group);
    }
}
