<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait MethodLockedField
{
    /**
     * Method locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $methodLocked;

    /**
     * @return bool
     */
    public function getMethodLocked(): ?bool
    {
        return $this->methodLocked;
    }

    /**
     * @param bool $methodLocked
     * @return $this
     */
    public function setMethodLocked(?bool $methodLocked): self
    {
        $this->methodLocked = $methodLocked;
        return $this;
    }
}