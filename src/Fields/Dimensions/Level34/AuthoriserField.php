<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

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

    public function getAuthoriserToString(): ?string
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
}
