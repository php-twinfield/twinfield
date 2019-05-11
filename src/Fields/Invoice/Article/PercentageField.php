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

    public function getPercentageToString(): ?string
    {
        return ($this->getPercentage()) ? 'true' : 'false';
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

    /**
     * @param string|null $percentageString
     * @return $this
     * @throws Exception
     */
    public function setPercentageFromString(?string $percentageString)
    {
        return $this->setPercentage(filter_var($percentageString, FILTER_VALIDATE_BOOLEAN));
    }
}