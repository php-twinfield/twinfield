<?php

namespace PhpTwinfield\Fields\VatCode;

use PhpTwinfield\VatGroup;

/**
 * The VAT group
 * Used by: VatCodeAccount
 *
 * @package PhpTwinfield\Traits
 */
trait GroupField
{
    /**
     * @var VatGroup|null
     */
    private $group;

    public function getGroup(): ?VatGroup
    {
        return $this->group;
    }

    /**
     * @return $this
     */
    public function setGroup(?VatGroup $group): self
    {
        $this->group = $group;
        return $this;
    }
}
