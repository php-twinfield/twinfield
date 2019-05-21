<?php

namespace PhpTwinfield\Fields\User;

use PhpTwinfield\UserRole;

/**
 * The user role
 * Used by: User
 *
 * @package PhpTwinfield\Traits
 */
trait RoleField
{
    /**
     * @var UserRole|null
     */
    private $role;

    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function getRoleToString(): ?string
    {
        if ($this->getRole() != null) {
            return $this->role->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setRole(?UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param string|null $roleString
     * @return $this
     * @throws Exception
     */
    public function setRoleFromString(?string $roleString)
    {
        $role = new UserRole();
        $role->setCode($roleString);
        return $this->setRole($role);
    }
}

