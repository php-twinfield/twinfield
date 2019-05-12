<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait Dim6LockedField
{
    /**
     * Dim 6 locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $dim6Locked;

    /**
     * @return bool
     */
    public function getDim6Locked(): ?bool
    {
        return $this->dim6Locked;
    }

    public function getDim6LockedToString(): ?string
    {
        return ($this->getDim6Locked()) ? 'true' : 'false';
    }

    /**
     * @param bool $dim6Locked
     * @return $this
     */
    public function setDim6Locked(?bool $dim6Locked): self
    {
        $this->dim6Locked = $dim6Locked;
        return $this;
    }

    /**
     * @param string|null $dim6LockedString
     * @return $this
     * @throws Exception
     */
    public function setDim6LockedFromString(?string $dim6LockedString)
    {
        return $this->setDim6Locked(filter_var($dim6LockedString, FILTER_VALIDATE_BOOLEAN));
    }
}