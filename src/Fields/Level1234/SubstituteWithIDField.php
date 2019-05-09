<?php

namespace PhpTwinfield\Fields\Level1234;

trait SubstituteWithIDField
{
    /**
     * Substitute with ID field
     * Used by: CustomerFinancials, FixedAssetFinancials, SupplierFinancials
     *
     * @var string|null
     */
    private $substituteWithID;

    /**
     * @return null|string
     */
    public function getSubstituteWithID(): ?string
    {
        return $this->substituteWithID;
    }

    /**
     * @param null|string $substituteWithID
     * @return $this
     */
    public function setSubstituteWithID(?string $substituteWithID): self
    {
        $this->substituteWithID = $substituteWithID;
        return $this;
    }
}