<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText3LockedField
{
    /**
     * Free text 3 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText3Locked;

    /**
     * @return bool
     */
    public function getFreeText3Locked(): ?bool
    {
        return $this->freeText3Locked;
    }
    
    /**
     * @param bool $freeText3Locked
     * @return $this
     */
    public function setFreeText3Locked(?bool $freeText3Locked): self
    {
        $this->freeText3Locked = $freeText3Locked;
        return $this;
    }
}