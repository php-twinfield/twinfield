<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait StatusLockedField
{
    /**
     * Status locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $statusLocked;

    /**
     * @return bool
     */
    public function getStatusLocked(): ?bool
    {
        return $this->statusLocked;
    }

    /**
     * @param bool $statusLocked
     * @return $this
     */
    public function setStatusLocked(?bool $statusLocked): self
    {
        $this->statusLocked = $statusLocked;
        return $this;
    }
}
