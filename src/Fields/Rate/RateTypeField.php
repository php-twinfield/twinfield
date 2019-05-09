<?php

namespace PhpTwinfield\Fields\Rate;

use PhpTwinfield\Enums\RateType;

trait RateTypeField
{
    /**
     * Rate type field
     * Used by: Rate
     *
     * @var type|null
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

    /**
     * @param string|null $typeString
     * @return $this
     * @throws Exception
     */
    public function setTypeFromString(?string $typeString)
    {
        return $this->setType(new RateType((string)$typeString));
    }
}