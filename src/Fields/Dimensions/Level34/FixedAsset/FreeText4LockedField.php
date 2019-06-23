<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText4LockedField
{
    /**
     * Free text 4 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText4Locked;

    /**
     * @return bool
     */
    public function getFreeText4Locked(): ?bool
    {
        return $this->freeText4Locked;
    }

    /**
     * @param bool $freeText4Locked
     * @return $this
     */
    public function setFreeText4Locked(?bool $freeText4Locked): self
    {
        $this->freeText4Locked = $freeText4Locked;
        return $this;
    }
}