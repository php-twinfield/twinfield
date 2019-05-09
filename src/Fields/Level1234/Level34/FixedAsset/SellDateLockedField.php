<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait SellDateLockedField
{
    /**
     * Sell date locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $sellDateLocked;

    /**
     * @return bool
     */
    public function getSellDateLocked(): ?bool
    {
        return $this->sellDateLocked;
    }

    public function getSellDateLockedToString(): ?string
    {
        return ($this->getSellDateLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $sellDateLocked
     * @return $this
     */
    public function setSellDateLocked(?bool $sellDateLocked): self
    {
        $this->sellDateLocked = $sellDateLocked;
        return $this;
    }

    /**
     * @param string|null $sellDateLockedString
     * @return $this
     * @throws Exception
     */
    public function setSellDateLockedFromString(?string $sellDateLockedString)
    {
        return $this->setSellDateLocked(filter_var($sellDateLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}