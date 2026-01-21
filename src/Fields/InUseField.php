<?php

namespace PhpTwinfield\Fields;

trait InUseField
{
    private $inuse;

    /**
     * READONLY attribute
     * Get the inuse attribute
     * @return mixed
     */
    public function getInuse()
    {
        return $this->inuse;
    }

    /**
     * READONLY attribute
     * Set the inuse attribute
     * @param mixed $inuse
     */
    public function setInuse($inuse): void
    {
        $this->inuse = $inuse;
    }
}
