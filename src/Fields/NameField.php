<?php

namespace PhpTwinfield\Fields;

trait NameField
{
    /**
     * Name field
     *
     * @var string|null
     */
    private $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }
}