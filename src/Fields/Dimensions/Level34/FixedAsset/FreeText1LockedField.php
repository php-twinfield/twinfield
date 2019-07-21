<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText1LockedField
{
    /**
     * Free text 1 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText1Locked;

    /**
     * @return bool
     */
    public function getFreeText1Locked(): ?bool
    {
        return $this->freeText1Locked;
    }

    /**
     * @param bool $freeText1Locked
     * @return $this
     */
    public function setFreeText1Locked(?bool $freeText1Locked): self
    {
        $this->freeText1Locked = $freeText1Locked;
        return $this;
    }
}
