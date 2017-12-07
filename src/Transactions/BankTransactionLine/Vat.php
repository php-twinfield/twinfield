<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;

class Vat extends Base
{
    use PerformanceFields;

    /**
     * @var string
     */
    private $vatCode;

    /**
     * Amount on which VAT was calculated in the currency of the bank transaction.
     *
     * @var Money
     */
    private $vatTurnover;

    /**
     * Amount on which VAT was calculated in base currency.
     *
     * @var Money
     */
    private $vatBaseTurnover;

    /**
     * Amount on which VAT was calculated in reporting currency.
     *
     * @var Money
     */
    private $vatRepTurnover;

    public function __construct()
    {
        $this->setType(LineType::VAT());
    }

    /**
     * Set the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken
     * as entered at the VAT code in Twinfield.
     *
     * @param string $dim1
     */
    public function setVatBalanceAccount(string $dim1)
    {
        $this->dim1 = $dim1;
    }

    /**
     * Based on the sum of the vat amounts of the individual bank transaction lins. In case of a bank addition credit.
     * In case of a bank withdrawal debit.
     *
     * @param DebitCredit $debitCredit
     * @return Vat
     */
    public function setDebitCredit(DebitCredit $debitCredit): self
    {
        return parent::setDebitCredit($debitCredit);
    }

    /**
     * VAT amount.
     *
     * @param Money $money
     */
    public function setValue(Money $money): void
    {
        parent::setValue($money);
    }

    /**
     * @return Money
     */
    public function getVatTurnover(): Money
    {
        return $this->vatTurnover;
    }

    /**
     * @return Money
     */
    public function getVatBaseTurnover(): Money
    {
        return $this->vatBaseTurnover;
    }

    /**
     * @return Money
     */
    public function getVatRepTurnover(): Money
    {
        return $this->vatRepTurnover;
    }

    /**
     * @return string
     */
    public function getVatCode(): string
    {
        return $this->vatCode;
    }
}