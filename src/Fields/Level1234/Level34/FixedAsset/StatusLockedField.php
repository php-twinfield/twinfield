<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait StatusLockedField
{
    /**
     * Status locked field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $statusLocked;

    /**
     * @return bool
     */
    public function getStatusLocked(): ?bool
    {
        return $this->statusLocked;
    }

    public function getStatusLockedToString(): ?string
    {
        return ($this->getStatusLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $statusLocked
     * @return $this
     */
    public function setStatusLocked(?bool $statusLocked): self
    {
        $this->statusLocked = $statusLocked;
        return $this;
    }

    /**
     * @param string|null $statusLockedString
     * @return $this
     * @throws Exception
     */
    public function setStatusLockedFromString(?string $statusLockedString)
    {
        return $this->setStatusLocked(filter_var($statusLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}