<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

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

    public function getNrOfPeriodsLockedToString(): ?string
    {
        return ($this->getNrOfPeriodsLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $nrOfPeriodsLockedString
     * @return $this
     * @throws Exception
     */
    public function setNrOfPeriodsLockedFromString(?string $nrOfPeriodsLockedString)
    {
        return $this->setNrOfPeriodsLocked(filter_var($nrOfPeriodsLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}