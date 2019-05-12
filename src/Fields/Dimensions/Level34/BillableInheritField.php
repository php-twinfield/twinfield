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

    /**
     * @param string|null $billableInheritString
     * @return $this
     * @throws Exception
     */
    public function setBillableInheritFromString(?string $billableInheritString)
    {
        return $this->setBillableInherit(filter_var($billableInheritString, FILTER_VALIDATE_BOOLEAN));
    }
}