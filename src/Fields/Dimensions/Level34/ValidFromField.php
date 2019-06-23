<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

/**
 * Valid from field
 * Used by: ActivityProjects, ProjectProjects
 *
 * @package PhpTwinfield\Traits
 */
trait ValidFromField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $validFrom;

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTimeInterface|null $validFrom
     * @return $this
     */
    public function setValidFrom(?\DateTimeInterface $validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }
}