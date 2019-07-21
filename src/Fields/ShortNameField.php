<?php

namespace PhpTwinfield\Fields;

trait ShortNameField
{
    /**
     * Short name field
     *
     * @var string|null
     */
    private $shortName;

    /**
     * @return null|string
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @param null|string $shortName
     * @return $this
     */
    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;
        return $this;
    }
}
