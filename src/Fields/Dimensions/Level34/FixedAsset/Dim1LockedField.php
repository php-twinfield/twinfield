<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim1LockedField
{
    /**
     * Dim 1 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim1Locked;

    /**
     * @return bool
     */
    public function getDim1Locked(): ?bool
    {
        return $this->dim1Locked;
    }
    
    /**
     * @param bool $dim1Locked
     * @return $this
     */
    public function setDim1Locked(?bool $dim1Locked): self
    {
        $this->dim1Locked = $dim1Locked;
        return $this;
    }
}