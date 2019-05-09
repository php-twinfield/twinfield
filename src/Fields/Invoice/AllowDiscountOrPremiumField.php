<?php

namespace PhpTwinfield\Fields\Invoice;

trait AllowDiscountOrPremiumField
{
    /**
     * Allow discount or premium field
     * Used by: Article, InvoiceLine
     *
     * @var bool
     */
    private $allowDiscountOrPremium;

    /**
     * @return bool
     */
    public function getAllowDiscountOrPremium(): ?bool
    {
        return $this->allowDiscountOrPremium;
    }

    public function getAllowDiscountOrPremiumToString(): ?string
    {
        return ($this->getAllowDiscountOrPremium()) ? 'true' : 'false';
    }

    /**
     * @param bool $allowDiscountOrPremium
     * @return $this
     */
    public function setAllowDiscountOrPremium(?bool $allowDiscountOrPremium): self
    {
        $this->allowDiscountOrPremium = $allowDiscountOrPremium;
        return $this;
    }

    /**
     * @param string|null $allowDiscountOrPremiumString
     * @return $this
     * @throws Exception
     */
    public function setAllowDiscountOrPremiumFromString(?string $allowDiscountOrPremiumString)
    {
        return $this->setAllowDiscountOrPremium(filter_var($allowDiscountOrPremiumString, FILTER_VALIDATE_BOOLEAN));
    }
}