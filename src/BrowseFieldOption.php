<?php

namespace PhpTwinfield;

class BrowseFieldOption
{
    /** @var string */
    private $code;

    /** @var string|null */
    private $name;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return BrowseFieldOption
     */
    public function setCode(string $code): BrowseFieldOption
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return BrowseFieldOption
     */
    public function setName(string $name): BrowseFieldOption
    {
        $this->name = $name;
        return $this;
    }
}
