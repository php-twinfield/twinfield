<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait SubCodeField
{
    /**
     * Sub code field
     * Used by: ArticleLine
     *
     * @var string|null
     */
    private $subCode;

    /**
     * @return null|string
     */
    public function getSubCode(): ?string
    {
        return $this->subCode;
    }

    /**
     * @param null|string $subCode
     * @return $this
     */
    public function setSubCode(?string $subCode): self
    {
        $this->subCode = $subCode;
        return $this;
    }
}