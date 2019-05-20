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

    public function getDestOfficeToString(): ?string
    {
        if ($this->getDestOffice() != null) {
            return $this->destOffice->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDestOffice(?Office $destOffice): self
    {
        $this->destOffice = $destOffice;
        return $this;
    }

    /**
     * @param string|null $officeCode
     * @return $this
     * @throws Exception
     */
    public function setDestOfficeFromString(?string $officeCode)
    {
        $destOffice = new Office();
        $destOffice->setCode($officeCode);
        return $this->setDestOffice($destOffice);
    }
}
