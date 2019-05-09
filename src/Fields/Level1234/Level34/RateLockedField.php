<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

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

    /**
     * @param string|null $rateLockedString
     * @return $this
     * @throws Exception
     */
    public function setRateLockedFromString(?string $rateLockedString)
    {
        return $this->setRateLocked(filter_var($rateLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}