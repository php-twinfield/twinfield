<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait CustomerLockedField
{
    /**
     * Customer locked field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $customerLocked;

    /**
     * @return bool
     */
    public function getCustomerLocked(): ?bool
    {
        return $this->customerLocked;
    }

    public function getCustomerLockedToString(): ?string
    {
        return ($this->getCustomerLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $customerLocked
     * @return $this
     */
    public function setCustomerLocked(?bool $customerLocked): self
    {
        $this->customerLocked = $customerLocked;
        return $this;
    }
}
