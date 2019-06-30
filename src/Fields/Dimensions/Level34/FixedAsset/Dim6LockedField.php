<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim6LockedField
{
    /**
     * Dim 6 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim6Locked;

    /**
     * @return bool
     */
    public function getDim6Locked(): ?bool
    {
        return $this->dim6Locked;
    }

    /**
     * @param bool $dim6Locked
     * @return $this
     */
    public function setDim6Locked(?bool $dim6Locked): self
    {
        $this->dim6Locked = $dim6Locked;
        return $this;
    }
}
