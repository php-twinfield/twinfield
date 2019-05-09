<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait ResidualValueLockedField
{
    /**
     * Residual value locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $residualValueLocked;

    /**
     * @return bool
     */
    public function getResidualValueLocked(): ?bool
    {
        return $this->residualValueLocked;
    }

    public function getResidualValueLockedToString(): ?string
    {
        return ($this->getResidualValueLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $residualValueLocked
     * @return $this
     */
    public function setResidualValueLocked(?bool $residualValueLocked): self
    {
        $this->residualValueLocked = $residualValueLocked;
        return $this;
    }

    /**
     * @param string|null $residualValueLockedString
     * @return $this
     * @throws Exception
     */
    public function setResidualValueLockedFromString(?string $residualValueLockedString)
    {
        return $this->setResidualValueLocked(filter_var($residualValueLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}