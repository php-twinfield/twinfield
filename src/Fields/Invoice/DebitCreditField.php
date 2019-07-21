<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Enums\InvoiceDebitCredit;

trait DebitCreditField
{
    /**
     * Debit/credit field
     * Used by: Invoice
     *
     * @var InvoiceDebitCredit|null
     */
    private $debitCredit;

    public function getDebitCredit(): ?InvoiceDebitCredit
    {
        return $this->debitCredit;
    }

    /**
     * @param InvoiceDebitCredit|null $debitCredit
     * @return $this
     */
    public function setDebitCredit(?InvoiceDebitCredit $debitCredit): self
    {
        $this->debitCredit = $debitCredit;
        return $this;
    }

    /**
     * @param string|null $debitCreditString
     * @return $this
     * @throws Exception
     */
    public function setDebitCreditFromString(?string $debitCreditString)
    {
        return $this->setDebitCredit(new InvoiceDebitCredit((string)$debitCreditString));
    }
}
