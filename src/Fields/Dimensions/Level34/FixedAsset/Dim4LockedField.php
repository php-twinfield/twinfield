<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim4LockedField
{
    /**
     * Dim 4 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim4Locked;

    /**
     * @return bool
     */
    public function getDim4Locked(): ?bool
    {
        return $this->dim4Locked;
    }

    /**
     * @param bool $dim4Locked
     * @return $this
     */
    public function setDim4Locked(?bool $dim4Locked): self
    {
        $this->dim4Locked = $dim4Locked;
        return $this;
    }
}
