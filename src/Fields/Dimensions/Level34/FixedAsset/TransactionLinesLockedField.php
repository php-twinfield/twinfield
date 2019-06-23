<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait TransactionLinesLockedField
{
    /**
     * Transaction lines locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $transactionLinesLocked;

    /**
     * @return bool
     */
    public function getTransactionLinesLocked(): ?bool
    {
        return $this->transactionLinesLocked;
    }

    /**
     * @param bool $transactionLinesLocked
     * @return $this
     */
    public function setTransactionLinesLocked(?bool $transactionLinesLocked): self
    {
        $this->transactionLinesLocked = $transactionLinesLocked;
        return $this;
    }
}