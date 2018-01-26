<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\VatCodeField;

class Detail extends Base
{
    use PerformanceFields;
    use VatCodeField;

    /**
     * VAT amount in the currency of the bank transaction.
     *
     * @var Money|null
     */
    private $vatValue;

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
     * @var Money|null
     */
    private $vatBaseValue;

    /**
     * VAT amount in reporting currency.
     *
     * @var Money|null
     */
    private $vatRepValue;

    public function __construct()
    {
        $this->setLineType(LineType::DETAIL());
    }

    /**
     * Set the customer or supplier balance account or profit and loss account.
     *
     * @param string $dim1
     * @return $this
     */
    public function setAccount(string $dim1)
    {
        return $this->setDim1($dim1);
    }

    /**
     * Set the customer or supplier or the cost center or empty.
     *
     * @param string $dim2
     * @return $this
     */
    public function setCustomerOrSupplierOrCostCenter(string $dim2)
    {
        return $this->setDim2($dim2);
    }

    /**
     * Set the project or asset or empty.
     *
     * @param string $dim3
     * @return $this
     */
    public function setProjectOrAsset(string $dim3)
    {
        return $this->setDim3($dim3);
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
     * @return Money|null
     */
    public function getVatValue(): ?Money
    {
        return $this->vatValue;
    }

    /**
     * @return Money|null
     */
    public function getVatBaseValue(): ?Money
    {
        return $this->vatBaseValue;
    }

    /**
     * @return Money|null
     */
    public function getVatRepValue(): ?Money
    {
        return $this->vatRepValue;
    }
}