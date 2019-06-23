<?php

namespace PhpTwinfield\Fields\Rate;

use PhpTwinfield\Enums\RateType;

trait TypeField
{
    /**
     * Type field
     * Used by: Rate
     *
     * @var RateType|null
     */
    private $type;

    public function getType(): ?RateType
    {
        return $this->type;
    }

    /**
     * @param RateType|null $type
     * @return $this
     */
    public function setType(?RateType $type): self
    {
        $this->type = $type;
        return $this;
    }
}