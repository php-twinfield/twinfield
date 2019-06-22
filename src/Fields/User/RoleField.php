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

    /**
     * @return $this
     */
    public function setRole(?UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }
}

