<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait FreeText1LockedField
{
    /**
     * Free text 1 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText1Locked;

    /**
     * @return bool
     */
    public function getFreeText1Locked(): ?bool
    {
        return $this->freeText1Locked;
    }

    public function getFreeText1LockedToString(): ?string
    {
        return ($this->getFreeText1Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $freeText1Locked
     * @return $this
     */
    public function setFreeText1Locked(?bool $freeText1Locked): self
    {
        $this->freeText1Locked = $freeText1Locked;
        return $this;
    }

    /**
     * @param string|null $freeText1LockedString
     * @return $this
     * @throws Exception
     */
    public function setFreeText1LockedFromString(?string $freeText1LockedString)
    {
        return $this->setFreeText1Locked(filter_var($freeText1LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}