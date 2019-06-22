<?php

namespace PhpTwinfield\Fields\User;

trait AcceptExtraCostField
{
    /**
     * Accept extra cost field
     * Used by: User
     *
     * @var bool
     */
    private $acceptExtraCost;

    /**
     * @return bool
     */
    public function getAcceptExtraCost(): ?bool
    {
        return $this->acceptExtraCost;
    }

    /**
     * @param bool $acceptExtraCost
     * @return $this
     */
    public function setAcceptExtraCost(?bool $acceptExtraCost): self
    {
        $this->acceptExtraCost = $acceptExtraCost;
        return $this;
    }
}