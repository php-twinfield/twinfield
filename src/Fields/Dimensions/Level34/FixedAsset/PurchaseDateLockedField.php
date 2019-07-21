<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait PurchaseDateLockedField
{
    /**
     * Purchase date locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $purchaseDateLocked;

    /**
     * @return bool
     */
    public function getPurchaseDateLocked(): ?bool
    {
        return $this->purchaseDateLocked;
    }

    /**
     * @param bool $purchaseDateLocked
     * @return $this
     */
    public function setPurchaseDateLocked(?bool $purchaseDateLocked): self
    {
        $this->purchaseDateLocked = $purchaseDateLocked;
        return $this;
    }
}
