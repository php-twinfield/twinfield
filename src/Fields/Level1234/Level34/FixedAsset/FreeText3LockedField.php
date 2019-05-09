<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait FreeText3LockedField
{
    /**
     * Free text 3 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText3Locked;

    /**
     * @return bool
     */
    public function getFreeText3Locked(): ?bool
    {
        return $this->freeText3Locked;
    }

    public function getFreeText3LockedToString(): ?string
    {
        return ($this->getFreeText3Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $freeText3Locked
     * @return $this
     */
    public function setFreeText3Locked(?bool $freeText3Locked): self
    {
        $this->freeText3Locked = $freeText3Locked;
        return $this;
    }

    /**
     * @param string|null $freeText3LockedString
     * @return $this
     * @throws Exception
     */
    public function setFreeText3LockedFromString(?string $freeText3LockedString)
    {
        return $this->setFreeText3Locked(filter_var($freeText3LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}