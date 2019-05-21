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

    public function getCurrencyToString(): ?string
    {
        if ($this->getCurrency() != null) {
            return $this->currency->getCode();
        } else {
            return null;
        }
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
     * @param string|null $currencyString
     * @return $this
     * @throws Exception
     */
    public function setCurrencyFromString(?string $currencyString)
    {
        $currency = new Currency();
        $currency->setCode($currencyString);

        return $this->setCurrency($currency);
    }

    /**
     * @return Money|null
     */
    public function getStartValue(): ?Money
    {
        return $this->startValue;
    }

    /**
     * @return float|null
     */
    public function getStartValueToFloat(): ?float
    {
        if ($this->getStartValue() != null) {
            return Util::formatMoney($this->getStartValue());
        } else {
            return 0;
        }
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

    /**
     * @param float|null $startValueFloat
     * @return $this
     * @throws Exception
     */
    public function setStartValueFromFloat(?float $startValueFloat)
    {
        if ((float)$startValueFloat) {
            $this->setStartValue(new Money(100 * $startValueFloat, new \Money\Currency($this->getCurrencyToString())));
        } else {
            $this->setStartValue(new Money(0, new \Money\Currency($this->getCurrencyToString())));
        }
    }

    public function getCloseValue(): Money
    {
        return $this->closeValue ?? new Money(0, new \Money\Currency($this->getCurrencyToString()));
    }

    /**
     * @return float|null
     */
    public function getCloseValueToFloat(): ?float
    {
        if ($this->getCloseValue() != null) {
            return Util::formatMoney($this->getCloseValue());
        } else {
            return 0;
        }
    }
}