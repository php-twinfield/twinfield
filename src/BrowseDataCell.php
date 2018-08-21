<?php

namespace PhpTwinfield;

class BrowseDataCell
{
    /** @var string */
    private $field;

    /** @var bool */
    private $hideForUser;

    /** @var string */
    private $type;

    /** @var mixed */
    private $value;

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return BrowseDataCell
     */
    public function setField(string $field): BrowseDataCell
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHideForUser(): bool
    {
        return $this->hideForUser;
    }

    /**
     * @param bool $hideForUser
     * @return BrowseDataCell
     */
    public function setHideForUser(bool $hideForUser): BrowseDataCell
    {
        $this->hideForUser = $hideForUser;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return BrowseDataCell
     */
    public function setType(string $type): BrowseDataCell
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return BrowseDataCell
     */
    public function setValue($value): BrowseDataCell
    {
        $this->value = $value;
        return $this;
    }
}
