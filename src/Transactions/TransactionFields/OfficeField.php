<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\Office;

/**
 * The office code.
 *
 * @package PhpTwinfield\Transactions\TransactionFields
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
    public function setOffice(Office $office): self
    {
        $this->office = $office;

        return $this;
    }
}
