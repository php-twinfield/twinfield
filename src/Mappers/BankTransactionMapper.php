<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\BankTransactionLine\Base;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;
use PhpTwinfield\Transactions\BankTransactionLine\Vat;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Util;

class BankTransactionMapper extends BaseMapper
{
    /**
     * @throws \PhpTwinfield\Exception
     */
    public static function map(\DOMDocument $document): BankTransaction
    {
        $bankTransaction = new BankTransaction();

        $element = $document->documentElement;

        if (!empty($element->getAttribute("autobalancevat"))) {
            $bankTransaction->setAutoBalanceVat(Util::parseBoolean($element->getAttribute("autobalancevat")));
        }

        if (!empty($element->getAttribute("raisewarning"))) {
            $bankTransaction->setRaiseWarning(Util::parseBoolean($element->getAttribute("raisewarning")));
        }

        self::setFromTagValue($document, "code", [$bankTransaction, "setCode"]);
        self::setFromTagValue($document, "office", [$bankTransaction, "setOffice"]);
        self::setFromTagValue($document, "date", [$bankTransaction, "setDate"]);
        self::setFromTagValue($document, "period", [$bankTransaction, "setPeriod"]);
        self::setFromTagValue($document, "startvalue", [$bankTransaction, "setStartValue"]);

        self::setFromTagValue($document, "statementnumber", [$bankTransaction, "setStatementNumber"]);
        self::setFromTagValue($document, "number", [$bankTransaction, "setNumber"]);

        /**  @var \DOMElement $lineElement */
        foreach ($element->getElementsByTagName("line") as $lineElement) {
            switch ($lineElement->getAttribute("type")) {
                case LineType::TOTAL():
                    $bankTransaction->addLine(self::createTotalBankTransactionLine($bankTransaction, $lineElement));
                    break;
                case LineType::DETAIL():
                    $bankTransaction->addLine(self::createDetailBankTransactionLine($bankTransaction, $lineElement));
                    break;
                case LineType::VAT():
                    $bankTransaction->addLine(self::createVatBankTransactionLine($bankTransaction, $lineElement));
                    break;
            }
        }

        return $bankTransaction;
    }

    private static function createTotalBankTransactionLine(BankTransaction $bankTransaction, \DOMElement $lineElement): Total
    {
        $line = new Total();
        self::setBankTransactionLineBaseFields($bankTransaction, $lineElement, $line);

        $line->setBankBalanceAccount(self::getField($lineElement, "dim1"));

        $vatTotal = self::getField($lineElement, "vattotal");
        if ($vatTotal) {
            $line->setVatTotal(Util::parseMoney($vatTotal, $bankTransaction->getCurrency()));
        }
        $vatBaseTotal = self::getField($lineElement, "vatbasetotal");
        if ($vatBaseTotal) {
            $line->setVatBaseTotal(Util::parseMoney($vatBaseTotal, $bankTransaction->getCurrency()));
        }
        $vatRepTotal = self::getField($lineElement, "vatreptotal");
        if ($vatRepTotal) {
            $line->setVatRepTotal(Util::parseMoney($vatRepTotal, $bankTransaction->getCurrency()));
        }

        return $line;
    }

    private static function createDetailBankTransactionLine(BankTransaction $bankTransaction, \DOMElement $lineElement): Detail
    {
        $line = new Detail();
        self::setBankTransactionLineBaseFields($bankTransaction, $lineElement, $line);
        self::setBankTransactionLinePerformanceFields($lineElement, $line);

        $line->setAccount(self::getField($lineElement, "dim1"));

        $customerOrSupplierOrCostCenter = self::getField($lineElement, "dim2");
        if ($customerOrSupplierOrCostCenter !== null) {
            $line->setCustomerOrSupplierOrCostCenter($customerOrSupplierOrCostCenter);
        }
        $vatCode = self::getField($lineElement, "vatcode");
        if ($vatCode !== null) {
            $line->setVatCode($vatCode);
        }
        $vatValue = self::getField($lineElement, "vatvalue");
        if ($vatValue) {
            $line->setVatValue(Util::parseMoney($vatValue, $bankTransaction->getCurrency()));
        }
        $vatBaseValue = self::getField($lineElement, "vatbasevalue");
        if ($vatBaseValue) {
            $line->setVatBaseValue(Util::parseMoney($vatBaseValue, $bankTransaction->getCurrency()));
        }
        $vatRepValue = self::getField($lineElement, "vatrepvalue");
        if ($vatRepValue) {
            $line->setVatRepValue(Util::parseMoney($vatRepValue, $bankTransaction->getCurrency()));
        }
        $projectOrAsset = self::getField($lineElement, "dim3");
        if ($projectOrAsset !== null) {
            $line->setProjectOrAsset($projectOrAsset);
        }

        return $line;
    }

    private static function createVatBankTransactionLine(BankTransaction $bankTransaction, \DOMElement $lineElement): Vat
    {
        $line = new Vat();
        self::setBankTransactionLineBaseFields($bankTransaction, $lineElement, $line);
        self::setBankTransactionLinePerformanceFields($lineElement, $line);

        $vatTurnover = self::getField($lineElement, "vatturnover");
        $line->setVatTurnover(Util::parseMoney($vatTurnover, $bankTransaction->getCurrency()));
        $vatBaseTurnover = self::getField($lineElement, "vatbaseturnover");
        $line->setVatBaseTurnover(Util::parseMoney($vatBaseTurnover, $bankTransaction->getCurrency()));

        $vatRepTurnover = self::getField($lineElement, "vatrepturnover");
        if ($vatRepTurnover) {
            $line->setVatRepTurnover(Util::parseMoney($vatRepTurnover, $bankTransaction->getCurrency()));
        }
        $vatCode = self::getField($lineElement, "vatcode");
        if ($vatCode !== null) {
            $line->setVatCode($vatCode);
        }
        $vatBalanceAccount = self::getField($lineElement, "dim1");
        if ($vatBalanceAccount !== null) {
            $line->setVatBalanceAccount($vatBalanceAccount);
        }

        return $line;
    }

    private static function setBankTransactionLineBaseFields(
        BankTransaction $bankTransaction,
        \DOMElement $lineElement,
        Base $line
    ): void {
        $line->setId($lineElement->getAttribute("id"));
        $value = self::getField($lineElement, 'value');
        $line->setValue(Util::parseMoney($value, $bankTransaction->getCurrency()));
        $line->setInvoiceNumber(self::getField($lineElement, "invoicenumber"));

        $description = self::getField($lineElement, "description");
        if ($description !== null) {
            $line->setDescription($description);
        }
        $debitCredit = self::getField($lineElement, 'debitcredit');
        if ($debitCredit) {
            $line->setDebitCredit(new DebitCredit($debitCredit));
        }
        $destOffice = self::getField($lineElement, "destoffice");
        if ($destOffice) {
            $line->setDestOffice(Office::fromCode($destOffice));
        }
        $freeChar = self::getField($lineElement, "freechar");
        if ($freeChar) {
            $line->setFreeChar($freeChar);
        }
        $comment = self::getField($lineElement, "comment");
        if ($comment !== null) {
            $line->setComment($comment);
        }
    }

    private static function setBankTransactionLinePerformanceFields(\DOMElement $lineElement, Base $line): void
    {
        $performanceType = self::getField($lineElement, "performancetype");
        if ($performanceType) {
            $line->setPerformanceType(new PerformanceType($performanceType));
        }
        $performanceCountry = self::getField($lineElement, "performancecountry");
        if ($performanceCountry) {
            $line->setPerformanceCountry($performanceCountry);
        }
        $performanceVatNumber = self::getField($lineElement, "performancevatnumber");
        if ($performanceVatNumber !== null) {
            $line->setPerformanceVatNumber($performanceVatNumber);
        }
        $performanceDate = self::getField($lineElement, "performancedate");
        if ($performanceDate) {
            $line->setPerformanceDate($performanceDate);
        }
    }
}