<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait Dim4LockedField
{
    /**
     * Dim 4 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim4Locked;

    /**
     * @return bool
     */
    public function getDim4Locked(): ?bool
    {
        return $this->dim4Locked;
    }

    public function getDim4LockedToString(): ?string
    {
        return ($this->getDim4Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $dim4Locked
     * @return $this
     */
    public function setDim4Locked(?bool $dim4Locked): self
    {
        $this->dim4Locked = $dim4Locked;
        return $this;
    }

    /**
     * @param string|null $dim4LockedString
     * @return $this
     * @throws Exception
     */
    public function setDim4LockedFromString(?string $dim4LockedString)
    {
        return $this->setDim4Locked(filter_var($dim4LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}