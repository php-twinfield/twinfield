<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

use PhpTwinfield\Article;

/**
 * The article
 * Used by: Customer
 *
 * @package PhpTwinfield\Traits
 */
trait DiscountArticleField
{
    /**
     * @var Article|null
     */
    private $discountArticle;

    public function getDiscountArticle(): ?Article
    {
        return $this->discountArticle;
    }

    /**
     * @return $this
     */
    public function setDiscountArticle(?Article $discountArticle): self
    {
        $this->discountArticle = $discountArticle;
        return $this;
    }
}
