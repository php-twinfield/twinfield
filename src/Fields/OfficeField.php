<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Office;

/**
 * The office
 *
 * @package PhpTwinfield\Traits
 */
trait OfficeField
{
    /**
     * @var Office|null
     */
    private $office;

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    /**
     * @return $this
     */
    public function setOffice(?Office $office): self
    {
        $this->office = $office;
        return $this;
    }
}
