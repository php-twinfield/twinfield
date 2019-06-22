<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait SellDateLockedField
{
    /**
     * Sell date locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $sellDateLocked;

    /**
     * @return bool
     */
    public function getSellDateLocked(): ?bool
    {
        return $this->sellDateLocked;
    }

    /**
     * @param bool $sellDateLocked
     * @return $this
     */
    public function setSellDateLocked(?bool $sellDateLocked): self
    {
        $this->sellDateLocked = $sellDateLocked;
        return $this;
    }
}