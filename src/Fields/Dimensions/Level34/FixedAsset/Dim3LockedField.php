<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim3LockedField
{
    /**
     * Dim 3 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim3Locked;

    /**
     * @return bool
     */
    public function getDim3Locked(): ?bool
    {
        return $this->dim3Locked;
    }

    /**
     * @param bool $dim3Locked
     * @return $this
     */
    public function setDim3Locked(?bool $dim3Locked): self
    {
        $this->dim3Locked = $dim3Locked;
        return $this;
    }
}
