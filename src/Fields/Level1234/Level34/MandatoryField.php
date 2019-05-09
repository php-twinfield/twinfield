<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

trait MandatoryField
{
    /**
     * Mandatory field
     * Used by: ActivityQuantity, ProjectQuantity
     *
     * @var bool
     */
    private $mandatory;

    /**
     * @return bool
     */
    public function getMandatory(): ?bool
    {
        return $this->mandatory;
    }

    public function getMandatoryToString(): ?string
    {
        return ($this->getMandatory()) ? 'true' : 'false';
    }

    /**
     * @param bool $mandatory
     * @return $this
     */
    public function setMandatory(?bool $mandatory): self
    {
        $this->mandatory = $mandatory;
        return $this;
    }

    /**
     * @param string|null $mandatoryString
     * @return $this
     * @throws Exception
     */
    public function setMandatoryFromString(?string $mandatoryString)
    {
        return $this->setMandatory(filter_var($mandatoryString, FILTER_VALIDATE_BOOLEAN));
    }
}