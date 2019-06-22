<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait LastDepreciationLockedField
{
    /**
     * Last depreciation locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $lastDepreciationLocked;

    /**
     * @return bool
     */
    public function getLastDepreciationLocked(): ?bool
    {
        return $this->lastDepreciationLocked;
    }

    /**
     * @param bool $lastDepreciationLocked
     * @return $this
     */
    public function setLastDepreciationLocked(?bool $lastDepreciationLocked): self
    {
        $this->lastDepreciationLocked = $lastDepreciationLocked;
        return $this;
    }
}