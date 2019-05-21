<?php

namespace PhpTwinfield\Fields\User;

trait DemoLockedField
{
    /**
     * Demo locked field
     * Used by: User
     *
     * @var bool
     */
    private $demoLocked;

    /**
     * @return bool
     */
    public function getDemoLocked(): ?bool
    {
        return $this->demoLocked;
    }

    public function getDemoLockedToString(): ?string
    {
        return ($this->getDemoLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $demoLocked
     * @return $this
     */
    public function setDemoLocked(?bool $demoLocked): self
    {
        $this->demoLocked = $demoLocked;
        return $this;
    }

    /**
     * @param string|null $demoLockedString
     * @return $this
     * @throws Exception
     */
    public function setDemoLockedFromString(?string $demoLockedString)
    {
        return $this->setDemoLocked(filter_var($demoLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}