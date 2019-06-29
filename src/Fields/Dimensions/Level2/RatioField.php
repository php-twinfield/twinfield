<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait RatioField
{
    /**
     * Ratio field
     * Used by: CustomerLine, SupplierLine
     *
     * @var float|null
     */
    private $ratio;

    /**
     * @return null|float
     */
    public function getRatio(): ?float
    {
        return $this->ratio;
    }

    /**
     * @param null|float $ratio
     * @return $this
     */
    public function setRatio(?float $ratio): self
    {
        $this->ratio = $ratio;
        return $this;
    }
}
