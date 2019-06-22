<?php

namespace PhpTwinfield\Fields\User;

use PhpTwinfield\Enums\UserType;

trait TypeField
{
    /**
     * Type field
     *
     * @var UserType|null
     */
    private $type;

    public function getType(): ?UserType
    {
        return $this->type;
    }

    /**
     * @param UserType|null $type
     * @return $this
     */
    public function setType(?UserType $type): self
    {
        $this->type = $type;
        return $this;
    }
}