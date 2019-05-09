<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

use PhpTwinfield\User;

/**
 * The user
 * Used by: CustomerCreditManagement
 *
 * @package PhpTwinfield\Traits
 */
trait ResponsibleUserField
{
    /**
     * @var User|null
     */
    private $responsibleUser;

    public function getResponsibleUser(): ?User
    {
        return $this->responsibleUser;
    }

    public function getResponsibleUserToCode(): ?string
    {
        if ($this->getResponsibleUser() != null) {
            return $this->responsibleUser->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setResponsibleUser(?User $responsibleUser): self
    {
        $this->responsibleUser = $responsibleUser;
        return $this;
    }

    /**
     * @param string|null $responsibleUserCode
     * @return $this
     * @throws Exception
     */
    public function setResponsibleUserFromCode(?string $responsibleUserCode)
    {
        $responsibleUser = new User();
        $responsibleUser->setCode($responsibleUserCode);
        return $this->setResponsibleUser($responsibleUser);
    }
}

