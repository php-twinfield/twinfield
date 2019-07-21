<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait AllowChangeVatCodeField
{
    /**
     * Allow change vat code field
     * Used by: Article
     *
     * @var bool
     */
    private $allowChangeVatCode;

    /**
     * @return bool
     */
    public function getAllowChangeVatCode(): ?bool
    {
        return $this->allowChangeVatCode;
    }

    /**
     * @param bool $allowChangeVatCode
     * @return $this
     */
    public function setAllowChangeVatCode(?bool $allowChangeVatCode): self
    {
        $this->allowChangeVatCode = $allowChangeVatCode;
        return $this;
    }
}
