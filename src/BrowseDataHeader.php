<?php

namespace PhpTwinfield;

class BrowseDataHeader
{
    /** @var string */
    private $code;

    /** @var string */
    private $label;

    /** @var bool */
    private $hideForUser;

    /** @var string */
    private $type;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return BrowseDataHeader
     */
    public function setCode(string $code): BrowseDataHeader
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return BrowseDataHeader
     */
    public function setLabel(string $label): BrowseDataHeader
    {
        $this->label = $label;
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
     * @return BrowseDataHeader
     */
    public function setHideForUser(bool $hideForUser): BrowseDataHeader
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
     * @return BrowseDataHeader
     */
    public function setType(string $type): BrowseDataHeader
    {
        $this->type = $type;
        return $this;
    }
}
