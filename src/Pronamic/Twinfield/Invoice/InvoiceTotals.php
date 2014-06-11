<?php
namespace Pronamic\Twinfield\Invoice;

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
