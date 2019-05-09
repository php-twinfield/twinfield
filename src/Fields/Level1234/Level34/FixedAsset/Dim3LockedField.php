<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait Dim3LockedField
{
    /**
     * Dim 3 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim3Locked;

    /**
     * @return bool
     */
    public function getDim3Locked(): ?bool
    {
        return $this->dim3Locked;
    }

    public function getDim3LockedToString(): ?string
    {
        return ($this->getDim3Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $dim3Locked
     * @return $this
     */
    public function setDim3Locked(?bool $dim3Locked): self
    {
        $this->dim3Locked = $dim3Locked;
        return $this;
    }

    /**
     * @param string|null $dim3LockedString
     * @return $this
     * @throws Exception
     */
    public function setDim3LockedFromString(?string $dim3LockedString)
    {
        return $this->setDim3Locked(filter_var($dim3LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}