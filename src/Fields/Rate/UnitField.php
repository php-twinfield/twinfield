<?php

namespace PhpTwinfield\Fields\Rate;

trait UnitField
{
    /**
     * Unit field
     * Used by: Rate
     *
     * @var int|null
     */
    private $unit;

    /**
     * @return null|int
     */
    public function getUnit(): ?int
    {
        return $this->unit;
    }

    /**
     * @param null|int $unit
     * @return $this
     */
    public function setUnit(?int $unit): self
    {
        $this->unit = $unit;
        return $this;
    }
}
