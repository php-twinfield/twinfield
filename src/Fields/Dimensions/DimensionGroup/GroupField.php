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

    public function getGroupToString(): ?string
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
     * @param string|null $groupString
     * @return $this
     * @throws Exception
     */
    public function setGroupFromString(?string $groupString)
    {
        $group = new DimensionGroup();
        $group->setCode($groupString);
        return $this->setGroup($group);
    }
}
