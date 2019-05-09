<?php

namespace PhpTwinfield\Fields\Invoice\Article;

use PhpTwinfield\Enums\ArticleType;

trait ArticleTypeField
{
    /**
     * Article type field
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

    /**
     * @param string|null $typeString
     * @return $this
     * @throws Exception
     */
    public function setTypeFromString(?string $typeString)
    {
        return $this->setType(new ArticleType((string)$typeString));
    }
}