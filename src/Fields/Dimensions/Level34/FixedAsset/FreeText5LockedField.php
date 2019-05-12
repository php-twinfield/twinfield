<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait FreeText5LockedField
{
    /**
     * Free text 5 locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $freeText5Locked;

    /**
     * @return bool
     */
    public function getFreeText5Locked(): ?bool
    {
        return $this->freeText5Locked;
    }

    public function getFreeText5LockedToString(): ?string
    {
        return ($this->getFreeText5Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $freeText5Locked
     * @return $this
     */
    public function setFreeText5Locked(?bool $freeText5Locked): self
    {
        $this->freeText5Locked = $freeText5Locked;
        return $this;
    }

    /**
     * @param string|null $freeText5LockedString
     * @return $this
     * @throws Exception
     */
    public function setFreeText5LockedFromString(?string $freeText5LockedString)
    {
        return $this->setFreeText5Locked(filter_var($freeText5LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}