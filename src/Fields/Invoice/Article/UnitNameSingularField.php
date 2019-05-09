<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait UnitNameSingularField
{
    /**
     * Unit name singular field
     * Used by: Article
     *
     * @var string|null
     */
    private $unitNameSingular;

    /**
     * @return null|string
     */
    public function getUnitNameSingular(): ?string
    {
        return $this->unitNameSingular;
    }

    /**
     * @param null|string $unitNameSingular
     * @return $this
     */
    public function setUnitNameSingular(?string $unitNameSingular): self
    {
        $this->unitNameSingular = $unitNameSingular;
        return $this;
    }
}