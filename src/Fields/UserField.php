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

    public function getUserToString(): ?string
    {
        if ($this->getUser() != null) {
            return $this->user->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string|null $userString
     * @return $this
     * @throws Exception
     */
    public function setUserFromString(?string $userString)
    {
        $user = new User();
        $user->setCode($userString);
        return $this->setUser($user);
    }
}

