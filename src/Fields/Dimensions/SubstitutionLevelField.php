<?php

namespace PhpTwinfield\Fields\Dimensions;

trait SubstitutionLevelField
{
    /**
     * Substitution level field
     * Used by: CustomerFinancials, FixedAssetFinancials, SupplierFinancials
     *
     * @var int|null
     */
    private $substitutionLevel;

    /**
     * @return null|int
     */
    public function getSubstitutionLevel(): ?int
    {
        return $this->substitutionLevel;
    }

    /**
     * @param null|int $substitutionLevel
     * @return $this
     */
    public function setSubstitutionLevel(?int $substitutionLevel): self
    {
        $this->substitutionLevel = $substitutionLevel;
        return $this;
    }
}
