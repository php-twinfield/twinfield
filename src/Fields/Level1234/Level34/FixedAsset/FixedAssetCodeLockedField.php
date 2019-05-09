<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait FixedAssetCodeLockedField
{
    /**
     * Fixed asset code locked field
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

    public function getCodeLockedToString(): ?string
    {
        return ($this->getCodeLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $codeLockedString
     * @return $this
     * @throws Exception
     */
    public function setCodeLockedFromString(?string $codeLockedString)
    {
        return $this->setCodeLocked(filter_var($codeLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}