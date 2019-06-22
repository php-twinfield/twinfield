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
    
    /**
     * @param bool $demoLocked
     * @return $this
     */
    public function setDemoLocked(?bool $demoLocked): self
    {
        $this->demoLocked = $demoLocked;
        return $this;
    }
}