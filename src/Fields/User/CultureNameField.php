<?php

namespace PhpTwinfield\Fields\User;

trait CultureNameField
{
    /**
     * Culture name field
     *
     * @var string|null
     */
    private $cultureName;

    /**
     * @return null|string
     */
    public function getCultureName(): ?string
    {
        return $this->cultureName;
    }

    /**
     * @param null|string $cultureName
     * @return $this
     */
    public function setCultureName(?string $cultureName): self
    {
        $this->cultureName = $cultureName;
        return $this;
    }
}
