<?php

namespace PhpTwinfield\Fields\User;

trait PasswordField
{
    /**
     * Password field
     *
     * @var string|null
     */
    private $password;

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
