<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;
use PhpTwinfield\Util;

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
     * @return float|null
     */
    public function getValueExclToFloat(): ?float
    {
        if ($this->getValueExcl() != null) {
            return Util::formatMoney($this->getValueExcl());
        } else {
            return 0;
        }
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

    /**
     * @param float|null $valueExclFloat
     * @return $this
     * @throws Exception
     */
    public function setValueExclFromFloat(?float $valueExclFloat)
    {
        if ((float)$valueExclFloat) {
            return $this->setValueExcl(Money::EUR(100 * $valueExclFloat));
        } else {
            return $this->setValueExcl(Money::EUR(0));
        }
    }
}