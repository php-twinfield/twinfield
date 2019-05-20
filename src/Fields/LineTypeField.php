<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Enums\LineType;

trait LineTypeField
{
    /**
     * Line type field
     * Used by: BaseTransactionLine, VatCodeAccount
     *
     * @var LineType|null
     */
    private $lineType;

    public function getLineType(): ?LineType
    {
        return $this->lineType;
    }

    /**
     * @param LineType|null $linetype
     * @return $this
     */
    public function setLineType(?LineType $lineType): self
    {
        $this->lineType = $lineType;
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