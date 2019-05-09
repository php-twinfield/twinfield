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
    
    public function getAllowChangeVatCodeToString(): ?string
    {
        return ($this->getAllowChangeVatCode()) ? 'true' : 'false';
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
    
    /**
     * @param string|null $allowChangeVatCodeString
     * @return $this
     * @throws Exception
     */
    public function setAllowChangeVatCodeFromString(?string $allowChangeVatCodeString)
    {
        return $this->setAllowChangeVatCode(filter_var($allowChangeVatCodeString, FILTER_VALIDATE_BOOLEAN));
    }
}