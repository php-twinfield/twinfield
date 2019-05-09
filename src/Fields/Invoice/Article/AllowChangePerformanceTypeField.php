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

    public function getAllowChangePerformanceTypeToString(): ?string
    {
        return ($this->getAllowChangePerformanceType()) ? 'true' : 'false';
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

    /**
     * @param string|null $allowChangePerformanceTypeString
     * @return $this
     * @throws Exception
     */
    public function setAllowChangePerformanceTypeFromString(?string $allowChangePerformanceTypeString)
    {
        return $this->setAllowChangePerformanceType(filter_var($allowChangePerformanceTypeString, FILTER_VALIDATE_BOOLEAN));
    }
}