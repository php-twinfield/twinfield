<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait AllowDecimalQuantityField
{
    /**
     * Allow decimal quantity field
     * Used by: Article
     *
     * @var bool
     */
    private $allowDecimalQuantity;

    /**
     * @return bool
     */
    public function getAllowDecimalQuantity(): ?bool
    {
        return $this->allowDecimalQuantity;
    }
    
    /**
     * @param bool $allowDecimalQuantity
     * @return $this
     */
    public function setAllowDecimalQuantity(?bool $allowDecimalQuantity): self
    {
        $this->allowDecimalQuantity = $allowDecimalQuantity;
        return $this;
    }
}