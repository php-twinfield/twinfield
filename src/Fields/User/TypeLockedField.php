<?php

namespace PhpTwinfield\Fields\User;

trait TypeLockedField
{
    /**
     * Type locked field
     * Used by: User
     *
     * @var bool
     */
    private $typeLocked;

    /**
     * @return bool
     */
    public function getTypeLocked(): ?bool
    {
        return $this->typeLocked;
    }

    /**
     * @param bool $typeLocked
     * @return $this
     */
    public function setTypeLocked(?bool $typeLocked): self
    {
        $this->typeLocked = $typeLocked;
        return $this;
    }
}
