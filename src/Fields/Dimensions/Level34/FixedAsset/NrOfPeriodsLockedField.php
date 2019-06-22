<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait NrOfPeriodsLockedField
{
    /**
     * Nr of periods locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $nrOfPeriodsLocked;

    /**
     * @return bool
     */
    public function getNrOfPeriodsLocked(): ?bool
    {
        return $this->nrOfPeriodsLocked;
    }

    /**
     * @param bool $nrOfPeriodsLocked
     * @return $this
     */
    public function setNrOfPeriodsLocked(?bool $nrOfPeriodsLocked): self
    {
        $this->nrOfPeriodsLocked = $nrOfPeriodsLocked;
        return $this;
    }
}