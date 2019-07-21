<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait StopValueLockedField
{
    /**
     * Stop value locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $stopValueLocked;

    /**
     * @return bool
     */
    public function getStopValueLocked(): ?bool
    {
        return $this->stopValueLocked;
    }

    /**
     * @param bool $stopValueLocked
     * @return $this
     */
    public function setStopValueLocked(?bool $stopValueLocked): self
    {
        $this->stopValueLocked = $stopValueLocked;
        return $this;
    }
}
