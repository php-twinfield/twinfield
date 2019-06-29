<?php

namespace PhpTwinfield\Fields\Transaction;

use Money\Money;
use PhpTwinfield\Currency;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

trait CloseAndStartValueFields
{
    /**
     * Currency code. Set to the currency of the corresponding bank day book when left empty.
     *
     * @var Currency
     */
    private $currency;

    /**
     * Opening balance. If not provided, the opening balance will be based on the previous bank statement.
     *
     * @var Money
     */
    private $startValue;

    /**
     * Closing balance. If not provided, the closing balance will be based on the opening balance and the total amount of the transactions.
     *
     * @var Money
     */
    private $closeValue;

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * Set the currency. Can only be done when the start value is still 0.
     *
     * @param Currency $currency
     * @return $this
     */
    public function setCurrency(?Currency $currency): self
    {
        Assert::true($this->startValue->isZero());
        $this->setStartValue(new Money(0, new \Money\Currency($currency->getCode())));

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getStartValue(): ?Money
    {
        return $this->startValue;
    }

    /**
     * @param Money|null $startValue
     * @return $this
     */
    public function setStartValue(?Money $startValue): void
    {
        $currency = new Currency();
        $currency->setCode($startValue->getCurrency());

        $this->currency = $currency;
        $this->startValue = $startValue;
        $this->closeValue = $startValue;
    }

    public function getCloseValue(): Money
    {
        return $this->closeValue ?? new Money(0, new \Money\Currency($this->getCurrency()->getCode()));
    }
}
