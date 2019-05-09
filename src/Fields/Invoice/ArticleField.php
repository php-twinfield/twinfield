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

    public function getArticleToCode(): ?string
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
     * @param string|null $articleCode
     * @return $this
     * @throws Exception
     */
    public function setArticleFromCode(?string $articleCode)
    {
        $article = new Article();
        $article->setCode($articleCode);
        return $this->setArticle($article);
    }
}
