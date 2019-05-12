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

    public function getAmountLockedToString(): ?string
    {
        return ($this->getAmountLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $amountLockedString
     * @return $this
     * @throws Exception
     */
    public function setAmountLockedFromString(?string $amountLockedString)
    {
        return $this->setAmountLocked(filter_var($amountLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}