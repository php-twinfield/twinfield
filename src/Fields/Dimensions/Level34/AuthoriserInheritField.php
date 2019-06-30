<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait AuthoriserInheritField
{
    /**
     * Authoriser inherit field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $authoriserInherit;

    /**
     * @return bool
     */
    public function getAuthoriserInherit(): ?bool
    {
        return $this->authoriserInherit;
    }

    public function getAuthoriserInheritToString(): ?string
    {
        return ($this->getAuthoriserInherit()) ? 'true' : 'false';
    }

    /**
     * @param bool $authoriserInherit
     * @return $this
     */
    public function setAuthoriserInherit(?bool $authoriserInherit): self
    {
        $this->authoriserInherit = $authoriserInherit;
        return $this;
    }
}
