<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

/**
 * Valid till field
 * Used by: ActivityProjects, ProjectProjects
 *
 * @package PhpTwinfield\Traits
 */
trait ValidTillField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $validTill;

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidTill(): ?\DateTimeInterface
    {
        return $this->validTill;
    }

    /**
     * @param \DateTimeInterface|null $validTill
     * @return $this
     */
    public function setValidTill(?\DateTimeInterface $validTill)
    {
        $this->validTill = $validTill;
        return $this;
    }
}