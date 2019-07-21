<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim2LockedField
{
    /**
     * Dim 2 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim2Locked;

    /**
     * @return bool
     */
    public function getDim2Locked(): ?bool
    {
        return $this->dim2Locked;
    }

    /**
     * @param bool $dim2Locked
     * @return $this
     */
    public function setDim2Locked(?bool $dim2Locked): self
    {
        $this->dim2Locked = $dim2Locked;
        return $this;
    }
}
