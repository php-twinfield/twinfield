<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\VatCodeField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTurnoverFields;

class Vat extends Base
{
    use PerformanceFields;
    use VatTurnoverFields;
    use VatCodeField;

    public function __construct()
    {
        $this->setLineType(LineType::VAT());
    }

    /**
     * Set the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken
     * as entered at the VAT code in Twinfield.
     *
     * @param string $dim1
     */
    public function setVatBalanceAccount(string $dim1)
    {
        $this->setDim1($dim1);
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
}