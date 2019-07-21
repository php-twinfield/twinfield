<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\User;

/**
 * The user
 * Used by: AssetMethod, BaseTransaction, Office, Rate, VatCode, VatCodePercentage
 *
 * @package PhpTwinfield\Traits
 */
trait UserField
{
    /**
     * @var User|null
     */
    private $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}

