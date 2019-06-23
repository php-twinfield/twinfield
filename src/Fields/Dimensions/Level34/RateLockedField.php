<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait RateLockedField
{
    /**
     * Rate locked field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $rateLocked;

    /**
     * @return bool
     */
    public function getRateLocked(): ?bool
    {
        return $this->rateLocked;
    }

    public function getRateLockedToString(): ?string
    {
        return ($this->getRateLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $rateLocked
     * @return $this
     */
    public function setRateLocked(?bool $rateLocked): self
    {
        $this->rateLocked = $rateLocked;
        return $this;
    }
}