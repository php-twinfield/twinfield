<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

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

    /**
     * @param string|null $billableLockedString
     * @return $this
     * @throws Exception
     */
    public function setBillableLockedFromString(?string $billableLockedString)
    {
        return $this->setBillableLocked(filter_var($billableLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}