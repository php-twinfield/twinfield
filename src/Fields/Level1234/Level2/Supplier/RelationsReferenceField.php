<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Supplier;

trait RelationsReferenceField
{
    /**
     * Relations reference field
     * Used by: SupplierFinancials
     *
     * @var string|null
     */
    private $relationsReference;

    /**
     * @return null|string
     */
    public function getRelationsReference(): ?string
    {
        return $this->relationsReference;
    }

    /**
     * @param null|string $relationsReference
     * @return $this
     */
    public function setRelationsReference(?string $relationsReference): self
    {
        $this->relationsReference = $relationsReference;
        return $this;
    }
}