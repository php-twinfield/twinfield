<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

use PhpTwinfield\Enums\FixedAssetsStatus;

trait FixedAssetsStatusField
{
    /**
     * Fixed assets status field
     * Used by: FixedAssetFixedAssets
     *
     * @var FixedAssetsStatus|null
     */
    private $status;

    public function getStatus(): ?FixedAssetsStatus
    {
        return $this->fixedAssetsStatus;
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

    /**
     * @param string|null $statusString
     * @return $this
     * @throws Exception
     */
    public function setStatusFromString(?string $statusString)
    {
        return $this->setStatus(new FixedAssetsStatus((string)$statusString));
    }
}