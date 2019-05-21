<?php

namespace PhpTwinfield\Fields;

trait EmailField
{
    /**
     * Email field
     * Used by: CustomerAddress, SupplierAddress, User
     *
     * @var string|null
     */
    private $email;

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }
}