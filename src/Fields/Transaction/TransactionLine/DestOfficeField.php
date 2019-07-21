<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use PhpTwinfield\Office;

/**
 * The office
 *
 * @package PhpTwinfield\Traits
 */
trait DestOfficeField
{
    /**
     * @var Office|null
     */
    private $destOffice;

    public function getDestOffice(): ?Office
    {
        return $this->destOffice;
    }

    /**
     * @return $this
     */
    public function setDestOffice(?Office $destOffice): self
    {
        $this->destOffice = $destOffice;
        return $this;
    }
}
