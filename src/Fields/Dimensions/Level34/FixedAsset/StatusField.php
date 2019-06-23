<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use PhpTwinfield\Enums\FixedAssetsStatus;

trait StatusField
{
    /**
     * Status field
     * Used by: FixedAssetFixedAssets
     *
     * @var FixedAssetsStatus|null
     */
    private $status;

    public function getStatus(): ?FixedAssetsStatus
    {
        return $this->status;
    }

    /**
     * @param FixedAssetsStatus|null $status
     * @return $this
     */
    public function setStatus(?FixedAssetsStatus $status): self
    {
        $this->status = $status;
        return $this;
    }
}