<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText2LockedField
{
    /**
     * Free text 2 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText2Locked;

    /**
     * @return bool
     */
    public function getFreeText2Locked(): ?bool
    {
        return $this->freeText2Locked;
    }

    public function getFreeText2LockedToString(): ?string
    {
        return ($this->getFreeText2Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $freeText2Locked
     * @return $this
     */
    public function setFreeText2Locked(?bool $freeText2Locked): self
    {
        $this->freeText2Locked = $freeText2Locked;
        return $this;
    }

    /**
     * @param string|null $freeText2LockedString
     * @return $this
     * @throws Exception
     */
    public function setFreeText2LockedFromString(?string $freeText2LockedString)
    {
        return $this->setFreeText2Locked(filter_var($freeText2LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}