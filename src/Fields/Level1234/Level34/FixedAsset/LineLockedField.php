<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait LineLockedField
{
    /**
     * Line locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $lineLocked;

    /**
     * @return bool
     */
    public function getLineLocked(): ?bool
    {
        return $this->lineLocked;
    }

    public function getLineLockedToString(): ?string
    {
        return ($this->getLineLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $lineLocked
     * @return $this
     */
    public function setLineLocked(?bool $lineLocked): self
    {
        $this->lineLocked = $lineLocked;
        return $this;
    }

    /**
     * @param string|null $lineLockedString
     * @return $this
     * @throws Exception
     */
    public function setLineLockedFromString(?string $lineLockedString)
    {
        return $this->setLineLocked(filter_var($lineLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}