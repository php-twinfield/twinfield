<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait PercentageLockedField
{
    /**
     * Percentage locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $percentageLocked;

    /**
     * @return bool
     */
    public function getPercentageLocked(): ?bool
    {
        return $this->percentageLocked;
    }

    public function getPercentageLockedToString(): ?string
    {
        return ($this->getPercentageLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $percentageLocked
     * @return $this
     */
    public function setPercentageLocked(?bool $percentageLocked): self
    {
        $this->percentageLocked = $percentageLocked;
        return $this;
    }

    /**
     * @param string|null $percentageLockedString
     * @return $this
     * @throws Exception
     */
    public function setPercentageLockedFromString(?string $percentageLockedString)
    {
        return $this->setPercentageLocked(filter_var($percentageLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}