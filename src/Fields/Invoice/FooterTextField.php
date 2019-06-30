<?php

namespace PhpTwinfield\Fields\Invoice;

trait FooterTextField
{
    /**
     * Footer text field
     * Used by: Invoice
     *
     * @var string|null
     */
    private $footerText;

    /**
     * @return null|string
     */
    public function getFooterText(): ?string
    {
        return $this->footerText;
    }

    /**
     * @param null|string $footerText
     * @return $this
     */
    public function setFooterText(?string $footerText): self
    {
        $this->footerText = $footerText;
        return $this;
    }
}
