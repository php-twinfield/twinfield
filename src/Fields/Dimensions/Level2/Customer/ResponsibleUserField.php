<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

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

    /**
     * @return $this
     */
    public function setResponsibleUser(?User $responsibleUser): self
    {
        $this->responsibleUser = $responsibleUser;
        return $this;
    }
}

