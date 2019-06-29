<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;

trait ValueExclField
{
    /**
     * Value excl field
     * Used by: InvoiceLine, InvoiceTotals
     *
     * @var Money|null
     */
    private $valueExcl;

    /**
     * @return Money|null
     */
    public function getValueExcl(): ?Money
    {
        return $this->valueExcl;
    }

    /**
     * @param Money|null $valueExcl
     * @return $this
     */
    public function setValueExcl(?Money $valueExcl)
    {
        $this->valueExcl = $valueExcl;

        return $this;
    }
}
