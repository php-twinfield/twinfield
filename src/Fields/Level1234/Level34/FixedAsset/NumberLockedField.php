<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait NumberLockedField
{
    /**
     * Number locked field
     * Used by: FixedAssetTransactionLine
     *
     * @var bool
     */
    private $numberLocked;

    /**
     * @return bool
     */
    public function getNumberLocked(): ?bool
    {
        return $this->numberLocked;
    }

    public function getNumberLockedToString(): ?string
    {
        return ($this->getNumberLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $numberLocked
     * @return $this
     */
    public function setNumberLocked(?bool $numberLocked): self
    {
        $this->numberLocked = $numberLocked;
        return $this;
    }

    /**
     * @param string|null $numberLockedString
     * @return $this
     * @throws Exception
     */
    public function setNumberLockedFromString(?string $numberLockedString)
    {
        return $this->setNumberLocked(filter_var($numberLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}