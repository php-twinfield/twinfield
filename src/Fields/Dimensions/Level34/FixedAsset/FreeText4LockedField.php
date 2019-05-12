<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText4LockedField
{
    /**
     * Free text 4 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText4Locked;

    /**
     * @return bool
     */
    public function getFreeText4Locked(): ?bool
    {
        return $this->freeText4Locked;
    }

    public function getFreeText4LockedToString(): ?string
    {
        return ($this->getFreeText4Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $freeText4Locked
     * @return $this
     */
    public function setFreeText4Locked(?bool $freeText4Locked): self
    {
        $this->freeText4Locked = $freeText4Locked;
        return $this;
    }

    /**
     * @param string|null $freeText4LockedString
     * @return $this
     * @throws Exception
     */
    public function setFreeText4LockedFromString(?string $freeText4LockedString)
    {
        return $this->setFreeText4Locked(filter_var($freeText4LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}