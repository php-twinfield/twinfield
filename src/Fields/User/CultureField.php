<?php

namespace PhpTwinfield\Fields\User;

use PhpTwinfield\Enums\Culture;

trait CultureField
{
    /**
     * Culture field
     *
     * @var Culture|null
     */
    private $culture;

    public function getCulture(): ?Culture
    {
        return $this->culture;
    }

    /**
     * @param Culture|null $culture
     * @return $this
     */
    public function setCulture(?Culture $culture): self
    {
        $this->culture = $culture;
        return $this;
    }

    /**
     * @param string|null $cultureString
     * @return $this
     * @throws Exception
     */
    public function setCultureFromString(?string $cultureString)
    {
        return $this->setCulture(new Culture((string)$cultureString));
    }
}