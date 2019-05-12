<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait MethodLockedField
{
    /**
     * Method locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $methodLocked;

    /**
     * @return bool
     */
    public function getMethodLocked(): ?bool
    {
        return $this->methodLocked;
    }

    public function getMethodLockedToString(): ?string
    {
        return ($this->getMethodLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $methodLocked
     * @return $this
     */
    public function setMethodLocked(?bool $methodLocked): self
    {
        $this->methodLocked = $methodLocked;
        return $this;
    }

    /**
     * @param string|null $methodLockedString
     * @return $this
     * @throws Exception
     */
    public function setMethodLockedFromString(?string $methodLockedString)
    {
        return $this->setMethodLocked(filter_var($methodLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}