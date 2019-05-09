<?php

namespace PhpTwinfield\Fields\VatCode;

use PhpTwinfield\Enums\LineType;

trait LineTypeField
{
    /**
     * Line type field
     * Used by: VatCodeAccount
     *
     * @var LineType|null
     */
    private $linetype;

    public function getLineType(): ?LineType
    {
        return $this->linetype;
    }

    /**
     * @param LineType|null $linetype
     * @return $this
     */
    public function setLineType(?LineType $linetype): self
    {
        $this->linetype = $linetype;
        return $this;
    }

    /**
     * @param string|null $lineTypeString
     * @return $this
     * @throws Exception
     */
    public function setLineTypeFromString(?string $lineTypeString)
    {
        return $this->setLineType(new LineType((string)$lineTypeString));
    }
}