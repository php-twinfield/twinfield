<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait LastDepreciationLockedField
{
    /**
     * Last depreciation locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $lastDepreciationLocked;

    /**
     * @return bool
     */
    public function getLastDepreciationLocked(): ?bool
    {
        return $this->lastDepreciationLocked;
    }

    public function getLastDepreciationLockedToString(): ?string
    {
        return ($this->getLastDepreciationLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $lastDepreciationLocked
     * @return $this
     */
    public function setLastDepreciationLocked(?bool $lastDepreciationLocked): self
    {
        $this->lastDepreciationLocked = $lastDepreciationLocked;
        return $this;
    }

    /**
     * @param string|null $lastDepreciationLockedString
     * @return $this
     * @throws Exception
     */
    public function setLastDepreciationLockedFromString(?string $lastDepreciationLockedString)
    {
        return $this->setLastDepreciationLocked(filter_var($lastDepreciationLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}