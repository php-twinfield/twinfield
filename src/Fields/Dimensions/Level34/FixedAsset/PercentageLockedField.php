<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait PercentageLockedField
{
    /**
     * Percentage locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $percentageLocked;

    /**
     * @return bool
     */
    public function getPercentageLocked(): ?bool
    {
        return $this->percentageLocked;
    }

    /**
     * @param bool $percentageLocked
     * @return $this
     */
    public function setPercentageLocked(?bool $percentageLocked): self
    {
        $this->percentageLocked = $percentageLocked;
        return $this;
    }
}
