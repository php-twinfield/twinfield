<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait StopValueLockedField
{
    /**
     * Stop value locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $stopValueLocked;

    /**
     * @return bool
     */
    public function getStopValueLocked(): ?bool
    {
        return $this->stopValueLocked;
    }

    public function getStopValueLockedToString(): ?string
    {
        return ($this->getStopValueLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $stopValueLocked
     * @return $this
     */
    public function setStopValueLocked(?bool $stopValueLocked): self
    {
        $this->stopValueLocked = $stopValueLocked;
        return $this;
    }

    /**
     * @param string|null $stopValueLockedString
     * @return $this
     * @throws Exception
     */
    public function setStopValueLockedFromString(?string $stopValueLockedString)
    {
        return $this->setStopValueLocked(filter_var($stopValueLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}