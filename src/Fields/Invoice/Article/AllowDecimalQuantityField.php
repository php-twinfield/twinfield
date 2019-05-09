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

    public function getAllowDecimalQuantityToString(): ?string
    {
        return ($this->getAllowDecimalQuantity()) ? 'true' : 'false';
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

    /**
     * @param string|null $allowDecimalQuantityString
     * @return $this
     * @throws Exception
     */
    public function setAllowDecimalQuantityFromString(?string $allowDecimalQuantityString)
    {
        return $this->setAllowDecimalQuantity(filter_var($allowDecimalQuantityString, FILTER_VALIDATE_BOOLEAN));
    }
}