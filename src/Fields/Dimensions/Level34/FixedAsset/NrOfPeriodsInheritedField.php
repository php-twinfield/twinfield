<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait NrOfPeriodsInheritedField
{
    /**
     * Nr of periods inherited field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $nrOfPeriodsInherited;

    /**
     * @return bool
     */
    public function getNrOfPeriodsInherited(): ?bool
    {
        return $this->nrOfPeriodsInherited;
    }

    /**
     * @param bool $nrOfPeriodsInherited
     * @return $this
     */
    public function setNrOfPeriodsInherited(?bool $nrOfPeriodsInherited): self
    {
        $this->nrOfPeriodsInherited = $nrOfPeriodsInherited;
        return $this;
    }
}