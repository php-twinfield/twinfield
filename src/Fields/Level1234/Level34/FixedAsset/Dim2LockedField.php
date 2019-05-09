<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait Dim2LockedField
{
    /**
     * Dim 2 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim2Locked;

    /**
     * @return bool
     */
    public function getDim2Locked(): ?bool
    {
        return $this->dim2Locked;
    }

    public function getDim2LockedToString(): ?string
    {
        return ($this->getDim2Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $dim2Locked
     * @return $this
     */
    public function setDim2Locked(?bool $dim2Locked): self
    {
        $this->dim2Locked = $dim2Locked;
        return $this;
    }

    /**
     * @param string|null $dim2LockedString
     * @return $this
     * @throws Exception
     */
    public function setDim2LockedFromString(?string $dim2LockedString)
    {
        return $this->setDim2Locked(filter_var($dim2LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}