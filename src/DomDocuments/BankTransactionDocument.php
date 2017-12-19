<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\Transactions\BankTransactionLine;
use PhpTwinfield\Util;

/**
 * Class BankTransactionDocument
 * @package PhpTwinfield\DomDocuments
 *
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions
 */
class BankTransactionDocument extends BaseDocument
{
    /**
     * Multiple transactions can be created at once by enclosing them by the transactions element.
     *
     * @return string
     */
    final protected function getRootTagName(): string
    {
        return "transactions";
    }

    public function addBankTransaction(BankTransaction $bankTransaction): void
    {
        $transaction = $this->createElement("transaction");

        $transaction->appendChild(new \DOMAttr("destiny", $bankTransaction->getDestiny()));

        if ($bankTransaction->isAutoBalanceVat() !== null) {
            $transaction->appendChild(
                $this->createBooleanAttribute("autobalancevat", $bankTransaction->isAutoBalanceVat())
            );
        }

        if ($bankTransaction->getRaiseWarning() !== null) {
            $transaction->appendChild(
                $this->createBooleanAttribute("raisewarning", $bankTransaction->getRaiseWarning())
            );
        }

        $header = $this->createElement("header");

        if ($bankTransaction->getCode() !== null) {
            $header->appendChild($this->createElement("code", $bankTransaction->getCode()));
        }

        $header->appendChild($this->createElement("office", $bankTransaction->getOffice()));

        if ($bankTransaction->getNumber() !== null) {
            $header->appendChild($this->createElement("number", $bankTransaction->getNumber()));
        }

        if ($bankTransaction->getPeriod() !== null) {
            $header->appendChild($this->createElement("period", $bankTransaction->getPeriod()));
        }

        if ($bankTransaction->getDate() !== null) {
            $header->appendChild($this->createElement("date", Util::formatDate($bankTransaction->getDate())));
        }

        if ($bankTransaction->getStatementnumber() !== null)  {
            $header->appendChild($this->createElement("statementnumber", $bankTransaction->getStatementnumber()));
        }

        $this->appendStartCloseValues($header, $bankTransaction);

        $this->appendFreeTextFields($header, $bankTransaction);
        $transaction->appendChild($header);

        $lines = $this->createElement("lines");
        $transaction->appendChild($lines);

        foreach ($bankTransaction->getLines() as $line) {
            $lines->appendChild($this->createTransactionLineElement($line));
        }

        $this->rootElement->appendChild($transaction);
    }

    protected function createTransactionLineElement(BankTransactionLine\Base $line): \DOMElement
    {
        $transaction = $this->createElement("line");
        $transaction->appendChild(new \DOMAttr("type", $line->getType()));

        if ($line->getId() !== null) {
            $transaction->appendChild(new \DOMAttr("id", $line->getId()));
        }

        if ($line->getDim1() !== null) {
            $transaction->appendChild($this->createElement("dim1", $line->getDim1()));
        }

        if ($line->getDim2() !== null) {
            $transaction->appendChild($this->createElement("dim2", $line->getDim2()));
        }

        if ($line->getDim3() !== null) {
            $transaction->appendChild($this->createElement("dim3", $line->getDim3()));
        }

        $this->appendValueValues($transaction, $line);

        if ($line->getInvoiceNumber() !== null) {
            $transaction->appendChild($this->createElement("invoicenumber", $line->getInvoiceNumber()));
        }

        if ($line->getDescription()) {
            $transaction->appendChild($this->createElement("description", $line->getDescription()));
        }

        if ($line instanceof BankTransactionLine\Total) {
            if ($line->getVatTotal() !== null) {
                $transaction->appendChild($this->createElement("vattotal", Util::formatMoney($line->getVatTotal())));
            }

            if ($line->getVatBaseTotal() !== null) {
                $transaction->appendChild($this->createElement("vatbasetotal", Util::formatMoney($line->getVatBaseTotal())));
            }

            if ($line->getVatRepTotal() !== null) {
                $transaction->appendChild($this->createElement("vatreptotal", Util::formatMoney($line->getVatRepTotal())));
            }
        }

        if ($line instanceof BankTransactionLine\Vat || $line instanceof BankTransactionLine\Detail) {
            if ($line->getVatCode()) {
                $transaction->appendChild($this->createElement("vatcode", $line->getVatCode()));
            }
        }

        if ($line instanceof BankTransactionLine\Detail) {
            if ($line->getVatValue() !== null) {
                $transaction->appendChild($this->createElement("vatvalue", Util::formatMoney($line->getVatValue())));
            }

            if ($line->getVatBaseValue() !== null) {
                $transaction->appendChild($this->createElement("vatbasevalue", Util::formatMoney($line->getVatBaseValue())));
            }

            if ($line->getVatRepValue() !== null) {
                $transaction->appendChild($this->createElement("vatrepvalue", Util::formatMoney($line->getVatRepValue())));
            }
        }

        if ($line instanceof BankTransactionLine\Detail || $line instanceof BankTransactionLine\Vat) {
            $this->appendPerformanceTypeFields($transaction, $line);
        }

        if ($line->getFreeChar()) {
            $transaction->appendChild($this->createElement("freechar", $line->getFreeChar()));
        }

        if ($line->getComment()) {
            $transaction->appendChild($this->createElement("comment", $line->getComment()));
        }

        return $transaction;
    }
}