<?php
namespace PhpTwinfield;

/**
 * @see https://accounting.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceTotals
{
    private $valueExcl;
    private $valueInc;

    public function getValueExcl()
    {
        return $this->valueExcl;
    }

    public function setValueExcl($valueExcl)
    {
        $this->valueExcl = $valueExcl;
    }

    public function getValueInc()
    {
        return $this->valueInc;
    }

    public function setValueInc($valueInc)
    {
        $this->valueInc = $valueInc;
    }
}
