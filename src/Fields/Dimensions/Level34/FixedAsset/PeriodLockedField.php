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

    public function getPeriodLockedToString(): ?string
    {
        return ($this->getPeriodLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $periodLockedString
     * @return $this
     * @throws Exception
     */
    public function setPeriodLockedFromString(?string $periodLockedString)
    {
        return $this->setPeriodLocked(filter_var($periodLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}