<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

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

    /**
     * @param bool $mandatory
     * @return $this
     */
    public function setMandatory(?bool $mandatory): self
    {
        $this->mandatory = $mandatory;
        return $this;
    }
}