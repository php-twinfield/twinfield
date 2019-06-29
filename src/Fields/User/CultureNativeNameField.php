<?php

namespace PhpTwinfield\Fields\User;

trait CultureNativeNameField
{
    /**
     * Culture native name field
     *
     * @var string|null
     */
    private $cultureNativeName;

    /**
     * @return null|string
     */
    public function getCultureNativeName(): ?string
    {
        return $this->cultureNativeName;
    }

    /**
     * @param null|string $cultureNativeName
     * @return $this
     */
    public function setCultureNativeName(?string $cultureNativeName): self
    {
        $this->cultureNativeName = $cultureNativeName;
        return $this;
    }
}
