<?php

namespace PhpTwinfield\Fields\User;

trait DemoField
{
    /**
     * Demo field
     * Used by: User
     *
     * @var bool
     */
    private $demo;

    /**
     * @return bool
     */
    public function getDemo(): ?bool
    {
        return $this->demo;
    }

    /**
     * @param bool $demo
     * @return $this
     */
    public function setDemo(?bool $demo): self
    {
        $this->demo = $demo;
        return $this;
    }
}
