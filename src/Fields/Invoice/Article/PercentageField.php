<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait PercentageField
{
    /**
     * Percentage field
     * Used by: Article
     *
     * @var bool
     */
    private $percentage;

    /**
     * @return bool
     */
    public function getPercentage(): ?bool
    {
        return $this->percentage;
    }

    /**
     * @param bool $percentage
     * @return $this
     */
    public function setPercentage(?bool $percentage): self
    {
        $this->percentage = $percentage;
        return $this;
    }
}