<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait PurchaseDateLockedField
{
    /**
     * Purchase date locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $purchaseDateLocked;

    /**
     * @return bool
     */
    public function getPurchaseDateLocked(): ?bool
    {
        return $this->purchaseDateLocked;
    }

    public function getPurchaseDateLockedToString(): ?string
    {
        return ($this->getPurchaseDateLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $purchaseDateLocked
     * @return $this
     */
    public function setPurchaseDateLocked(?bool $purchaseDateLocked): self
    {
        $this->purchaseDateLocked = $purchaseDateLocked;
        return $this;
    }

    /**
     * @param string|null $purchaseDateLockedString
     * @return $this
     * @throws Exception
     */
    public function setPurchaseDateLockedFromString(?string $purchaseDateLockedString)
    {
        return $this->setPurchaseDateLocked(filter_var($purchaseDateLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}