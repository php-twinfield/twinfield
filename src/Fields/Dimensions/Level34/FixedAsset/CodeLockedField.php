<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait CodeLockedField
{
    /**
     * Code locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $codeLocked;

    /**
     * @return bool
     */
    public function getCodeLocked(): ?bool
    {
        return $this->codeLocked;
    }

    /**
     * @param bool $codeLocked
     * @return $this
     */
    public function setCodeLocked(?bool $codeLocked): self
    {
        $this->codeLocked = $codeLocked;
        return $this;
    }
}
