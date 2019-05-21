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

    public function getRoleLockedToString(): ?string
    {
        return ($this->getRoleLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $roleLockedString
     * @return $this
     * @throws Exception
     */
    public function setRoleLockedFromString(?string $roleLockedString)
    {
        return $this->setRoleLocked(filter_var($roleLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}