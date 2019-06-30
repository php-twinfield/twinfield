<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait AllowChangePerformanceTypeField
{
    /**
     * Allow change performance type field
     * Used by: Article
     *
     * @var bool
     */
    private $allowChangePerformanceType;

    /**
     * @return bool
     */
    public function getAllowChangePerformanceType(): ?bool
    {
        return $this->allowChangePerformanceType;
    }

    /**
     * @param bool $allowChangePerformanceType
     * @return $this
     */
    public function setAllowChangePerformanceType(?bool $allowChangePerformanceType): self
    {
        $this->allowChangePerformanceType = $allowChangePerformanceType;
        return $this;
    }
}
