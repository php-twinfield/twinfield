<?php

namespace PhpTwinfield\Fields\Level1234;

use PhpTwinfield\Enums\GeneralLedgerType;

trait GeneralLedgerTypeField
{
    /**
     * General ledger type field
     * Used by: CustomerChildValidation, GeneralLedgerChildValidation, SupplierChildValidation
     *
     * @var GeneralLedgerType|null
     */
    private $type;

    public function getType(): ?GeneralLedgerType
    {
        return $this->type;
    }

    /**
     * @param GeneralLedgerType|null $type
     * @return $this
     */
    public function setType(?GeneralLedgerType $type): self
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
        return $this->setType(new GeneralLedgerType((string)$typeString));
    }
}