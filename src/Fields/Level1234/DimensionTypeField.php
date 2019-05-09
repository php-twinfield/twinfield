<?php

namespace PhpTwinfield\Fields\Level1234;

use PhpTwinfield\DimensionType;

/**
 * The dimension type
 * Used by: Activity, CostCenter, Customer, DimensionGroupDimension, FixedAsset, GeneralLedger, Project, Supplier
 *
 * @package PhpTwinfield\Traits
 */
trait DimensionTypeField
{
    /**
     * @var DimensionType|null
     */
    private $type;

    public function getType(): ?DimensionType
    {
        return $this->type;
    }

    public function getTypeToCode(): ?string
    {
        if ($this->getType() != null) {
            return $this->type->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setType(?DimensionType $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string|null $typeCode
     * @return $this
     * @throws Exception
     */
    public function setTypeFromCode(?string $typeCode)
    {
        $type = new DimensionType();
        $type->setCode($typeCode);
        return $this->setType($type);
    }
}
