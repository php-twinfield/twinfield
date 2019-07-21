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

    /**
     * @param bool $allowDiscountOrPremium
     * @return $this
     */
    public function setAllowDiscountOrPremium(?bool $allowDiscountOrPremium): self
    {
        $this->allowDiscountOrPremium = $allowDiscountOrPremium;
        return $this;
    }
}
