<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

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

    public function getDiscountArticleToCode(): ?string
    {
        if ($this->getDiscountArticle() != null) {
            return $this->discountArticle->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDiscountArticle(?Article $discountArticle): self
    {
        $this->discountArticle = $discountArticle;
        return $this;
    }

    /**
     * @param string|null $discountArticleCode
     * @return $this
     * @throws Exception
     */
    public function setDiscountArticleFromCode(?string $discountArticleCode)
    {
        $discountArticle = new Article();
        $discountArticle->setCode($discountArticleCode);
        return $this->setDiscountArticle($discountArticle);
    }
}
