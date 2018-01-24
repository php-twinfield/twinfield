<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;

class Total extends Base
{
    /**
     * The total VAT amount in the currency of the bank transaction.
     *
     * @var Money|null
     */
    private $vatTotal;

    /**
     * The total VAT amount in base currency.
     *
     * @var Money|null
     */
    private $vatBaseTotal;

    /**
     * The total VAT amount in reporting currency.
     *
     * @var Money|null
     */
    private $vatRepTotal;

    public function __construct()
    {
        $this->setLineType(LineType::TOTAL());
    }

    public function setBankBalanceAccount(string $dim1)
    {
        $this->setDim1($dim1);
    }

    /**
     * Based on the sum of the individual bank transaction lines. In case of a bank addition debit. In case of a bank
     * withdrawal credit.
     *
     * @param DebitCredit $debitCredit
     * @return $this
     */
    public function setDebitCredit(DebitCredit $debitCredit)
    {
        return parent::setDebitCredit($debitCredit);
    }

    /**
     * Amount including VAT.
     *
     * @param Money $money
     */
    public function setValue(Money $money): void
    {
        parent::setValue($money);
    }

    /**
     * @return Money|null
     */
    public function getVatTotal(): ?Money
    {
        return $this->vatTotal;
    }

    /**
     * The total VAT amount in the currency of the bank transaction.
     *
     * @param Money $vatTotal
     */
    public function setVatTotal(Money $vatTotal): void
    {
        $this->vatTotal = $vatTotal;
    }

    /**
     * @return Money|null
     */
    public function getVatBaseTotal(): ?Money
    {
        return $this->vatBaseTotal;
    }

    /**
     * The total VAT amount in base currency.
     *
     * @param Money $vatBaseTotal
     */
    public function setVatBaseTotal(Money $vatBaseTotal): void
    {
        $this->vatBaseTotal = $vatBaseTotal;
    }

    /**
     * @return Money|null
     */
    public function getVatRepTotal(): ?Money
    {
        return $this->vatRepTotal;
    }

    /**
     * @param Money $vatRepTotal
     */
    public function setVatRepTotal(Money $vatRepTotal): void
    {
        $this->vatRepTotal = $vatRepTotal;
    }
}