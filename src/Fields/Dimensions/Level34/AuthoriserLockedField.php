<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait AuthoriserLockedField
{
    /**
     * Authoriser locked field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $authoriserLocked;

    /**
     * @return bool
     */
    public function getAuthoriserLocked(): ?bool
    {
        return $this->authoriserLocked;
    }

    public function getAuthoriserLockedToString(): ?string
    {
        return ($this->getAuthoriserLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $authoriserLocked
     * @return $this
     */
    public function setAuthoriserLocked(?bool $authoriserLocked): self
    {
        $this->authoriserLocked = $authoriserLocked;
        return $this;
    }
}