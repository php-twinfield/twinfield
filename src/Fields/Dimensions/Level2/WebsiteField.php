<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait WebsiteField
{
    /**
     * Website field
     * Used by: Customer, Supplier
     *
     * @var string|null
     */
    private $website;

    /**
     * @return null|string
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param null|string $website
     * @return $this
     */
    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }
}
