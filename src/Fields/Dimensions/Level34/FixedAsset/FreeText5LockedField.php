<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText5LockedField
{
    /**
     * Free text 5 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText5Locked;

    /**
     * @return bool
     */
    public function getFreeText5Locked(): ?bool
    {
        return $this->freeText5Locked;
    }

    /**
     * @param bool $freeText5Locked
     * @return $this
     */
    public function setFreeText5Locked(?bool $freeText5Locked): self
    {
        $this->freeText5Locked = $freeText5Locked;
        return $this;
    }
}
