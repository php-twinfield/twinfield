<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait NumberLockedField
{
    /**
     * Number locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $numberLocked;

    /**
     * @return bool
     */
    public function getNumberLocked(): ?bool
    {
        return $this->numberLocked;
    }

    /**
     * @param bool $numberLocked
     * @return $this
     */
    public function setNumberLocked(?bool $numberLocked): self
    {
        $this->numberLocked = $numberLocked;
        return $this;
    }
}