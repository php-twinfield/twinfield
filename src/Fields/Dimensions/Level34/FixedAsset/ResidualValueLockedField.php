<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait ResidualValueLockedField
{
    /**
     * Residual value locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $residualValueLocked;

    /**
     * @return bool
     */
    public function getResidualValueLocked(): ?bool
    {
        return $this->residualValueLocked;
    }

    /**
     * @param bool $residualValueLocked
     * @return $this
     */
    public function setResidualValueLocked(?bool $residualValueLocked): self
    {
        $this->residualValueLocked = $residualValueLocked;
        return $this;
    }
}
