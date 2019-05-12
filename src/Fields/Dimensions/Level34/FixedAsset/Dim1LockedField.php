<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim1LockedField
{
    /**
     * Dim 1 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim1Locked;

    /**
     * @return bool
     */
    public function getDim1Locked(): ?bool
    {
        return $this->dim1Locked;
    }

    public function getDim1LockedToString(): ?string
    {
        return ($this->getDim1Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $dim1Locked
     * @return $this
     */
    public function setDim1Locked(?bool $dim1Locked): self
    {
        $this->dim1Locked = $dim1Locked;
        return $this;
    }

    /**
     * @param string|null $dim1LockedString
     * @return $this
     * @throws Exception
     */
    public function setDim1LockedFromString(?string $dim1LockedString)
    {
        return $this->setDim1Locked(filter_var($dim1LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}