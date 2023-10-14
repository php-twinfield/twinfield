<?php

namespace PhpTwinfield\Fields;

trait BehaviourField
{
    use BehaviourField;

    private $behaviour;

    /**
     * READONLY attribute
     * Get the behaviour attribute
     * @return mixed
     */
    public function getBehaviour()
    {
        return $this->behaviour;
    }

    /**
     * READONLY attribute
     * Set the behaviour attribute
     * @param mixed $behaviour
     */
    public function setBehaviour($behaviour): void
    {
        $this->behaviour = $behaviour;
    }
}
