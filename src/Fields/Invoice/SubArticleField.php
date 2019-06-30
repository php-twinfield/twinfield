<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\ArticleLine;

/**
 * The sub article
 * Used by: InvoiceLine
 *
 * @package PhpTwinfield\Traits
 */
trait SubArticleField
{
    /**
     * @var ArticleLine|null
     */
    private $subArticle;

    public function getSubArticle(): ?ArticleLine
    {
        return $this->subArticle;
    }

    public function getSubArticleToString(): ?string
    {
        if ($this->getSubArticle() != null) {
            return $this->subArticle->getSubCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setSubArticle(?ArticleLine $subArticle): self
    {
        $this->subArticle = $subArticle;
        return $this;
    }

    /**
     * @param string|null $subArticleString
     * @return $this
     * @throws Exception
     */
    public function setSubArticleFromString(?string $subArticleString)
    {
        $subArticle = new ArticleLine();
        $subArticle->setSubCode($subArticleString);
        return $this->setSubArticle($subArticle);
    }
}
