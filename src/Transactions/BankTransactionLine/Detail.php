<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;

class Detail extends Base
{
    use PerformanceFields;

    /**
     * @var mixed
     */
    private $vatCode;

    /**
     * VAT amount in the currency of the bank transaction.
     *
     * @var Money
     */
    private $vatValue;

    /**
     * @param mixed $vatCode
     * @return Detail
     */
    public function setVatCode($vatCode)
    {
        $this->vatCode = $vatCode;

        return $this;
    }

    /**
     * @param Money $vatValue
     * @return Detail
     */
    public function setVatValue(Money $vatValue): Detail
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    /**
     * @param Money $vatBaseValue
     * @return Detail
     */
    public function setVatBaseValue(Money $vatBaseValue): Detail
    {
        $this->vatBaseValue = $vatBaseValue;

        return $this;
    }

    /**
     * @param Money $vatRepValue
     * @return Detail
     */
    public function setVatRepValue(Money $vatRepValue): Detail
    {
        $this->vatRepValue = $vatRepValue;

        return $this;
    }

    /**
     * VAT amount in base currency.
     *
     * @var Money
     */
    private $vatBaseValue;

    /**
     * VAT amount in reporting currency.
     *
     * @var Money
     */
    private $vatRepValue;

    public function __construct()
    {
        $this->setType(LineType::DETAIL());
    }

    /**
     * Set the customer or supplier balance account or profit and loss account.
     *
     * @param string $dim1
     */
    public function setAccount(string $dim1)
    {
        $this->dim1 = $dim1;
    }

    /**
     * Set the customer or supplier or the cost center or empty.
     *
     * @param string $dim2
     */
    public function setCustomerOrSupplierOrCostCenter(string $dim2)
    {
        $this->dim2 = $dim2;
    }

    /**
     * Set the project or asset or empty.
     *
     * @param string $dim3
     */
    public function setProjectOrAsset(string $dim3)
    {
        $this->dim3 = $dim3;
    }

    /**
     * Credit in case money is received and debit in case money is paid.
     *
     * @param DebitCredit $debitCredit
     * @return Detail
     */
    public function setDebitCredit(DebitCredit $debitCredit): self
    {
        return parent::setDebitCredit($debitCredit);
    }

    /**
     * Amount without VAT.
     *
     * @param Money $money
     */
    public function setValue(Money $money): void
    {
        parent::setValue($money);
    }

    /**
     * @return mixed
     */
    public function getVatCode()
    {
        return $this->vatCode;
    }

    /**
     * @return Money
     */
    public function getVatValue(): Money
    {
        return $this->vatValue;
    }

    /**
     * @return Money
     */
    public function getVatBaseValue(): Money
    {
        return $this->vatBaseValue;
    }

    /**
     * @return Money
     */
    public function getVatRepValue(): Money
    {
        return $this->vatRepValue;
    }
}