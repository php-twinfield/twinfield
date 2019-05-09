<?php

namespace PhpTwinfield\Fields\Invoice;

trait HeaderTextField
{
    /**
     * Header text field
     * Used by: Invoice
     *
     * @var string|null
     */
    private $headerText;

    /**
     * @return null|string
     */
    public function getHeaderText(): ?string
    {
        return $this->headerText;
    }

    /**
     * @param null|string $headerText
     * @return $this
     */
    public function setHeaderText(?string $headerText): self
    {
        $this->headerText = $headerText;
        return $this;
    }
}