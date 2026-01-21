<?php

namespace PhpTwinfield\Fields;

trait TouchedField
{
    private $touched;

    /**
     * READONLY attribute
     * Get the touched attribute
     * @return mixed
     */
    public function getTouched()
    {
        return $this->touched;
    }

    /**
     * READONLY attribute
     * Set the touched attribute
     * @param int $touched
     */
    public function setTouched(int $touched): void
    {
        $this->touched = $touched;
    }
}
