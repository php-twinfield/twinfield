<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait BillableInheritField
{
    /**
     * Billable inherit field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $billableInherit;

    /**
     * @return bool
     */
    public function getBillableInherit(): ?bool
    {
        return $this->billableInherit;
    }

    public function getBillableInheritToString(): ?string
    {
        return ($this->getBillableInherit()) ? 'true' : 'false';
    }

    /**
     * @param bool $billableInherit
     * @return $this
     */
    public function setBillableInherit(?bool $billableInherit): self
    {
        $this->billableInherit = $billableInherit;
        return $this;
    }
}