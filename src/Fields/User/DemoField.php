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

    public function getDemoToString(): ?string
    {
        return ($this->getDemo()) ? 'true' : 'false';
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

    /**
     * @param string|null $demoString
     * @return $this
     * @throws Exception
     */
    public function setDemoFromString(?string $demoString)
    {
        return $this->setDemo(filter_var($demoString, FILTER_VALIDATE_BOOLEAN));
    }
}