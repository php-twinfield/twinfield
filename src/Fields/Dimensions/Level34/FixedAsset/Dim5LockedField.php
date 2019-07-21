<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim5LockedField
{
    /**
     * Dim 5 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim5Locked;

    /**
     * @return bool
     */
    public function getDim5Locked(): ?bool
    {
        return $this->dim5Locked;
    }

    /**
     * @param bool $dim5Locked
     * @return $this
     */
    public function setDim5Locked(?bool $dim5Locked): self
    {
        $this->dim5Locked = $dim5Locked;
        return $this;
    }
}
