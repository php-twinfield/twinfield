<?php

namespace PhpTwinfield\Fields;

trait NrOfPeriodsField
{
    /**
     * Nr of periods field
     * Used by: AssetMethod, FixedAssetFixedAssets
     *
     * @var int|null
     */
    private $nrOfPeriods;

    /**
     * @return null|int
     */
    public function getNrOfPeriods(): ?int
    {
        return $this->nrOfPeriods;
    }

    /**
     * @param null|int $nrOfPeriods
     * @return $this
     */
    public function setNrOfPeriods(?int $nrOfPeriods): self
    {
        $this->nrOfPeriods = $nrOfPeriods;
        return $this;
    }
}