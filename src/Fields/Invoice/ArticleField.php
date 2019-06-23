<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Article;

/**
 * The article
 * Used by: InvoiceLine 
 *
 * @package PhpTwinfield\Traits
 */
trait ArticleField
{
    /**
     * @var Article|null
     */
    private $article;

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @return $this
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;
        return $this;
    }
}
