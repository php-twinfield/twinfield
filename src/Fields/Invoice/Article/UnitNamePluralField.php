<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait UnitNamePluralField
{
    /**
     * Unit name plural field
     * Used by: Article
     *
     * @var string|null
     */
    private $unitNamePlural;

    /**
     * @return null|string
     */
    public function getUnitNamePlural(): ?string
    {
        return $this->unitNamePlural;
    }

    /**
     * @param null|string $unitNamePlural
     * @return $this
     */
    public function setUnitNamePlural(?string $unitNamePlural): self
    {
        $this->unitNamePlural = $unitNamePlural;
        return $this;
    }
}
