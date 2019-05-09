<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

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

    public function getTransactionLinesLockedToString(): ?string
    {
        return ($this->getTransactionLinesLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $transactionLinesLockedString
     * @return $this
     * @throws Exception
     */
    public function setTransactionLinesLockedFromString(?string $transactionLinesLockedString)
    {
        return $this->setTransactionLinesLocked(filter_var($transactionLinesLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}