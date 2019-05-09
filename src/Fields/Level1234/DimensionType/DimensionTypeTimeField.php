<?php

namespace PhpTwinfield\Fields\Level1234\DimensionType;

trait DimensionTypeTimeField
{
    /**
     * Dimension type time field
     * Used by: DimensionTypeLevels
     *
     * @var int|null
     */
    private $time;

    /**
     * @return null|int
     */
    public function getTime(): ?int
    {
        return $this->time;
    }

    /**
     * @param null|int $time
     * @return $this
     */
    public function setTime(?int $time): self
    {
        $this->time = $time;
        return $this;
    }
}