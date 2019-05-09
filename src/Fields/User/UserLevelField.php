<?php

namespace PhpTwinfield\Fields\User;

trait UserLevelField
{
    /**
     * User level field
     * Used by: User
     *
     * @var string|null
     */
    private $level;

    /**
     * @return null|string
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * @param null|string $level
     * @return $this
     */
    public function setLevel(?string $level): self
    {
        $this->level = $level;
        return $this;
    }
}