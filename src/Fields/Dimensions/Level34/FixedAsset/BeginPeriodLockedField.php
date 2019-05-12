<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait BeginPeriodLockedField
{
    /**
     * Begin period locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $beginPeriodLocked;

    /**
     * @return bool
     */
    public function getBeginPeriodLocked(): ?bool
    {
        return $this->beginPeriodLocked;
    }

    public function getBeginPeriodLockedToString(): ?string
    {
        return ($this->getBeginPeriodLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $beginPeriodLocked
     * @return $this
     */
    public function setBeginPeriodLocked(?bool $beginPeriodLocked): self
    {
        $this->beginPeriodLocked = $beginPeriodLocked;
        return $this;
    }

    /**
     * @param string|null $beginPeriodLockedString
     * @return $this
     * @throws Exception
     */
    public function setBeginPeriodLockedFromString(?string $beginPeriodLockedString)
    {
        return $this->setBeginPeriodLocked(filter_var($beginPeriodLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}