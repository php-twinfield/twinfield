<?php

namespace PhpTwinfield\Fields\Level1234\Level34;

trait InvoiceDescriptionField
{
    /**
     * Invoice description field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var string|null
     */
    private $invoiceDescription;

    /**
     * @return null|string
     */
    public function getInvoiceDescription(): ?string
    {
        return $this->invoiceDescription;
    }

    /**
     * @param null|string $invoiceDescription
     * @return $this
     */
    public function setInvoiceDescription(?string $invoiceDescription): self
    {
        $this->invoiceDescription = $invoiceDescription;
        return $this;
    }
}