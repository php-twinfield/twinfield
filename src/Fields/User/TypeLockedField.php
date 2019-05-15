<?php

namespace PhpTwinfield\Fields\User;

trait TypeLockedField
{
    /**
     * Type locked field
     * Used by: User
     *
     * @var bool
     */
    private $typeLocked;

    /**
     * @return bool
     */
    public function getTypeLocked(): ?bool
    {
        return $this->typeLocked;
    }

    public function getTypeLockedToString(): ?string
    {
        return ($this->getTypeLocked()) ? 'true' : 'false';
    }

    /**
     * @param bool $typeLocked
     * @return $this
     */
    public function setTypeLocked(?bool $typeLocked): self
    {
        $this->typeLocked = $typeLocked;
        return $this;
    }

    /**
     * @param string|null $typeLockedString
     * @return $this
     * @throws Exception
     */
    public function setTypeLockedFromString(?string $typeLockedString)
    {
        return $this->setTypeLocked(filter_var($typeLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}