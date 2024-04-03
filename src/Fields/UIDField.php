<?php

namespace PhpTwinfield\Fields;

trait UIDField
{
    private $UID;

    /**
     * READONLY attribute
     * Get the UID attribute
     * @return mixed
     */
    public function getUID()
    {
        return $this->UID;
    }

    /**
     * READONLY attribute
     * Set the UID attribute
     * @param mixed $UID
     */
    public function setUID($UID): void
    {
        $this->UID = $UID;
    }
}
