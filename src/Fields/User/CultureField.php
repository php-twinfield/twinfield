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
}
