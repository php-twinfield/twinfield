<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

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

    /**
     * @param string|null $customerLockedString
     * @return $this
     * @throws Exception
     */
    public function setCustomerLockedFromString(?string $customerLockedString)
    {
        return $this->setCustomerLocked(filter_var($customerLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}