<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

trait BillableField
{
    /**
     * Billable field
     * Used by: ActivityProjects, ActivityQuantity, ProjectsProjects, ProjectsQuantity
     *
     * @var bool
     */
    private $billable;

    /**
     * @return bool
     */
    public function getBillable(): ?bool
    {
        return $this->billable;
    }

    public function getBillableToString(): ?string
    {
        return ($this->getBillable()) ? 'true' : 'false';
    }

    /**
     * @param bool $billable
     * @return $this
     */
    public function setBillable(?bool $billable): self
    {
        $this->billable = $billable;
        return $this;
    }

    /**
     * @param string|null $billableString
     * @return $this
     * @throws Exception
     */
    public function setBillableFromString(?string $billableString)
    {
        return $this->setBillable(filter_var($billableString, FILTER_VALIDATE_BOOLEAN));
    }
}