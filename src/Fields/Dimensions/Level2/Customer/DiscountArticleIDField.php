<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait DiscountArticleIDField
{
    /**
     * Discount article ID field
     * Used by: Customer
     *
     * @var string|null
     */
    private $discountArticleID;

    /**
     * @return null|string
     */
    public function getDiscountArticleID(): ?string
    {
        return $this->discountArticleID;
    }

    /**
     * @param null|string $discountArticleID
     * @return $this
     */
    public function setDiscountArticleID(?string $discountArticleID): self
    {
        $this->discountArticleID = $discountArticleID;
        return $this;
    }
}