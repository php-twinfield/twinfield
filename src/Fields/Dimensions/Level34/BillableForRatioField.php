<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait BillableForRatioField
{
    /**
     * Billable for ratio field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $billableForRatio;

    /**
     * @return bool
     */
    public function getBillableForRatio(): ?bool
    {
        return $this->billableForRatio;
    }

    public function getBillableForRatioToString(): ?string
    {
        return ($this->getBillableForRatio()) ? 'true' : 'false';
    }

    /**
     * @param bool $billableForRatio
     * @return $this
     */
    public function setBillableForRatio(?bool $billableForRatio): self
    {
        $this->billableForRatio = $billableForRatio;
        return $this;
    }

    /**
     * @param string|null $billableForRatioString
     * @return $this
     * @throws Exception
     */
    public function setBillableForRatioFromString(?string $billableForRatioString)
    {
        return $this->setBillableForRatio(filter_var($billableForRatioString, FILTER_VALIDATE_BOOLEAN));
    }
}