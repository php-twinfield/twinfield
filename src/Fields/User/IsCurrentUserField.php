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

    public function getIsCurrentUserToString(): ?string
    {
        return ($this->getIsCurrentUser()) ? 'true' : 'false';
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

    /**
     * @param string|null $isCurrentUserString
     * @return $this
     * @throws Exception
     */
    public function setIsCurrentUserFromString(?string $isCurrentUserString)
    {
        return $this->setIsCurrentUser(filter_var($isCurrentUserString, FILTER_VALIDATE_BOOLEAN));
    }
}