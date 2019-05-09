<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

use PhpTwinfield\User;

/**
 * The user
 * Used by: ActivityProjects, ProjectsProjects
 *
 * @package PhpTwinfield\Traits
 */
trait AuthoriserField
{
    /**
     * @var User|null
     */
    private $authoriser;

    public function getAuthoriser(): ?User
    {
        return $this->authoriser;
    }

    public function getAuthoriserToCode(): ?string
    {
        if ($this->getAuthoriser() != null) {
            return $this->authoriser->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setAuthoriser(?User $authoriser): self
    {
        $this->authoriser = $authoriser;
        return $this;
    }

    /**
     * @param string|null $authoriserCode
     * @return $this
     * @throws Exception
     */
    public function setAuthoriserFromCode(?string $authoriserCode)
    {
        $authoriser = new User();
        $authoriser->setCode($authoriserCode);
        return $this->setAuthoriser($authoriser);
    }
}
