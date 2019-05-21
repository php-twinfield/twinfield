<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatBaseTurnoverField
{
    /**
     * VAT base turnover field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatBaseTurnover;

    /**
     * @return Money|null
     */
    public function getVatBaseTurnover(): ?Money
    {
        return $this->vatBaseTurnover;
    }

    /**
     * @return float|null
     */
    public function getVatBaseTurnoverToFloat(): ?float
    {
        if ($this->getVatBaseTurnover() != null) {
            return Util::formatMoney($this->getVatBaseTurnover());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatBaseTurnover
     * @return $this
     */
    public function setVatBaseTurnover(?Money $vatBaseTurnover)
    {
        $this->vatBaseTurnover = $vatBaseTurnover;

        return $this;
    }

    /**
     * @param float|null $vatBaseTurnoverFloat
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTurnoverFromFloat(?float $vatBaseTurnoverFloat)
    {
        if ((float)$vatBaseTurnoverFloat) {
            return $this->setVatBaseTurnover(Money::EUR(100 * $vatBaseTurnoverFloat));
        } else {
            return $this->setVatBaseTurnover(Money::EUR(0));
        }
    }
}