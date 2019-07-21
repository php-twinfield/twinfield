<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait BeginPeriodLockedField
{
    /**
     * Begin period locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $beginPeriodLocked;

    /**
     * @return bool
     */
    public function getBeginPeriodLocked(): ?bool
    {
        return $this->beginPeriodLocked;
    }

    /**
     * @param bool $beginPeriodLocked
     * @return $this
     */
    public function setBeginPeriodLocked(?bool $beginPeriodLocked): self
    {
        $this->beginPeriodLocked = $beginPeriodLocked;
        return $this;
    }
}
