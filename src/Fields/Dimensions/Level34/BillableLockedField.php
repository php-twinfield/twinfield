<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait BillableLockedField
{
    /**
     * Billable locked field
     * Used by: ActivityProjects, ActivityQuantity, ProjectsProjects, ProjectsQuantity
     *
     * @var bool
     */
    private $billableLocked;

    /**
     * @return bool
     */
    public function getBillableLocked(): ?bool
    {
        return $this->billableLocked;
    }

    public function getBillableLockedToString(): ?string
    {
        return ($this->getBillableLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $billableLocked
     * @return $this
     */
    public function setBillableLocked(?bool $billableLocked): self
    {
        $this->billableLocked = $billableLocked;
        return $this;
    }
}