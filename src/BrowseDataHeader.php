<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;

class BrowseDataHeader extends BaseObject implements HasCodeInterface
{
    use CodeField;

    /** @var string */
    private $label;

    /** @var bool */
    private $hideForUser;

    /** @var string */
    private $type;

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
