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

    public function getAcceptExtraCostToString(): ?string
    {
        return ($this->getAcceptExtraCost()) ? 'true' : 'false';
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

    /**
     * @param string|null $acceptExtraCostString
     * @return $this
     * @throws Exception
     */
    public function setAcceptExtraCostFromString(?string $acceptExtraCostString)
    {
        return $this->setAcceptExtraCost(filter_var($acceptExtraCostString, FILTER_VALIDATE_BOOLEAN));
    }
}