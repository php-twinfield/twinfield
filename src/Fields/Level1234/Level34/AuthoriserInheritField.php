<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

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

    /**
     * @param string|null $authoriserInheritString
     * @return $this
     * @throws Exception
     */
    public function setAuthoriserInheritFromString(?string $authoriserInheritString)
    {
        return $this->setAuthoriserInherit(filter_var($authoriserInheritString, FILTER_VALIDATE_BOOLEAN));
    }
}