<?php

namespace PhpTwinfield\Fields\Invoice\Article;

use PhpTwinfield\Enums\ArticleType;

trait TypeField
{
    /**
     * Type field
     * Used by: Article
     *
     * @var ArticleType|null
     */
    private $type;

    public function getType(): ?ArticleType
    {
        return $this->type;
    }

    /**
     * @param ArticleType|null $type
     * @return $this
     */
    public function setType(?ArticleType $type): self
    {
        $this->type = $type;
        return $this;
    }
}
