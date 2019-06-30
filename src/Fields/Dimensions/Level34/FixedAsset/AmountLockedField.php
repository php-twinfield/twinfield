<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait AmountLockedField
{
    /**
     * Amount locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $amountLocked;

    /**
     * @return bool
     */
    public function getAmountLocked(): ?bool
    {
        return $this->amountLocked;
    }

    /**
     * @param bool $amountLocked
     * @return $this
     */
    public function setAmountLocked(?bool $amountLocked): self
    {
        $this->amountLocked = $amountLocked;
        return $this;
    }
}
