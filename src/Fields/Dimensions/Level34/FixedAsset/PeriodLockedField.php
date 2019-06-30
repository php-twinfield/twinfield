<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait PeriodLockedField
{
    /**
     * Period locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $periodLocked;

    /**
     * @return bool
     */
    public function getPeriodLocked(): ?bool
    {
        return $this->periodLocked;
    }

    /**
     * @param bool $periodLocked
     * @return $this
     */
    public function setPeriodLocked(?bool $periodLocked): self
    {
        $this->periodLocked = $periodLocked;
        return $this;
    }
}
