<?php

namespace PhpTwinfield\Fields;

trait DescriptionField
{
    /**
     * Description field
     * Used by: CustomerLine, CustomerPostingRule, InvoiceLine, SupplierLine, SupplierPostingRule
     *
     * @var string|null
     */
    private $description;

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}