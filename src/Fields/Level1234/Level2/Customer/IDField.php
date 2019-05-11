<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

trait IDField
{
    /**
     * ID field
     * Used by: CustomerCollectMandate
     *
     * @var string|null
     */
    private $ID;

    /**
     * @return null|string
     */
    public function getID(): ?string
    {
        return $this->ID;
    }

    /**
     * @param null|string $ID
     * @return $this
     */
    public function setID(?string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }
}