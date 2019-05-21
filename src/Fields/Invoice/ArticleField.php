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

    public function getArticleToString(): ?string
    {
        if ($this->getArticle() != null) {
            return $this->article->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @param string|null $articleString
     * @return $this
     * @throws Exception
     */
    public function setArticleFromString(?string $articleString)
    {
        $article = new Article();
        $article->setCode($articleString);
        return $this->setArticle($article);
    }
}
