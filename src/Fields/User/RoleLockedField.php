<?php

namespace PhpTwinfield\Fields\User;

trait RoleLockedField
{
    /**
     * Role locked field
     * Used by: User
     *
     * @var bool
     */
    private $roleLocked;

    /**
     * @return bool
     */
    public function getRoleLocked(): ?bool
    {
        return $this->roleLocked;
    }
    
    /**
     * @param bool $roleLocked
     * @return $this
     */
    public function setRoleLocked(?bool $roleLocked): self
    {
        $this->roleLocked = $roleLocked;
        return $this;
    }
}