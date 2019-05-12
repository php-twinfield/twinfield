<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait RateInheritField
{
    /**
     * Rate inherit field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $rateInherit;

    /**
     * @return bool
     */
    public function getRateInherit(): ?bool
    {
        return $this->rateInherit;
    }

    public function getRateInheritToString(): ?string
    {
        return ($this->getRateInherit()) ? 'true' : 'false';
    }

    /**
     * @param bool $rateInherit
     * @return $this
     */
    public function setRateInherit(?bool $rateInherit): self
    {
        $this->rateInherit = $rateInherit;
        return $this;
    }

    /**
     * @param string|null $rateInheritString
     * @return $this
     * @throws Exception
     */
    public function setRateInheritFromString(?string $rateInheritString)
    {
        return $this->setRateInherit(filter_var($rateInheritString, FILTER_VALIDATE_BOOLEAN));
    }
}