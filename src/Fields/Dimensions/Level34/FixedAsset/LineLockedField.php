<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait LineLockedField
{
    /**
     * Line locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $lineLocked;

    /**
     * @return bool
     */
    public function getLineLocked(): ?bool
    {
        return $this->lineLocked;
    }

    /**
     * @param bool $lineLocked
     * @return $this
     */
    public function setLineLocked(?bool $lineLocked): self
    {
        $this->lineLocked = $lineLocked;
        return $this;
    }
}