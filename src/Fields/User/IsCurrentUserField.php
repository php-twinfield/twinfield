<?php

namespace PhpTwinfield\Fields\User;

trait IsCurrentUserField
{
    /**
     * Is current user field
     * Used by: User
     *
     * @var bool
     */
    private $isCurrentUser;

    /**
     * @return bool
     */
    public function getIsCurrentUser(): ?bool
    {
        return $this->isCurrentUser;
    }

    /**
     * @param bool $isCurrentUser
     * @return $this
     */
    public function setIsCurrentUser(?bool $isCurrentUser): self
    {
        $this->isCurrentUser = $isCurrentUser;
        return $this;
    }
}