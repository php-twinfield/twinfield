<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionType;

trait TimeField
{
    /**
     * Time field
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
