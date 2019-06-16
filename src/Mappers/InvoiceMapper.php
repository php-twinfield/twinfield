<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\InvoiceVatLine;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class InvoiceMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Invoice entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Invoice
     * @throws \PhpTwinfield\Exception
     */
     public static function map(Response $response)
    {
        // Generate new Invoice object
        $invoice = new Invoice();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/salesinvoice element
        $invoiceElement = $responseDOM->documentElement;

        // Set the result attribute
        $invoice->setResult($invoiceElement->getAttribute('result'));

        // Set the invoice elements from the invoice element
        $invoice->setBank(self::parseObjectAttribute(\PhpTwinfield\CashBankBook::class, $invoice, $invoiceElement, 'bank'))
            ->setCurrency(self::parseObjectAttribute(\PhpTwinfield\Currency::class, $invoice, $invoiceElement, 'currency'))
            ->setCustomer(self::parseObjectAttribute(\PhpTwinfield\Customer::class, $invoice, $invoiceElement, 'customer'))
            ->setDeliverAddressNumber(self::getField($invoiceElement, 'deliveraddressnumber', $invoice))
            ->setDueDate(self::parseDateAttribute(self::getField($invoiceElement, 'duedate', $invoice)))
            ->setFooterText(self::getField($invoiceElement, 'footertext', $invoice))
            ->setHeaderText(self::getField($invoiceElement, 'headertext', $invoice))
            ->setInvoiceAddressNumber(self::getField($invoiceElement, 'invoiceaddressnumber', $invoice))
            ->setInvoiceDate(self::parseDateAttribute(self::getField($invoiceElement, 'invoicedate', $invoice)))
            ->setInvoiceNumber(self::getField($invoiceElement, 'invoicenumber', $invoice))
            ->setInvoiceType(self::parseObjectAttribute(\PhpTwinfield\InvoiceType::class, $invoice, $invoiceElement, 'invoicetype'))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $invoice, $invoiceElement, 'office'))
            ->setPaymentMethod(self::parseEnumAttribute(\PhpTwinfield\Enums\PaymentMethod::class, self::getField($invoiceElement, 'paymentmethod', $invoice)))
            ->setPeriod(self::getField($invoiceElement, 'period', $invoice))
            ->setPerformanceDate(self::parseDateAttribute(self::getField($invoiceElement, 'performancedate', $invoice)))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\InvoiceStatus::class, self::getField($invoiceElement, 'status', $invoice)));

        // Get the totals element
        $totalsElement = $responseDOM->getElementsByTagName('totals')->item(0);

        if ($totalsElement !== null) {
            // Make a new temporary InvoiceTotals class
            $invoiceTotals = new InvoiceTotals();

            // Set the invoice totals elements from the totals element
            $invoiceTotals->setValueExcl(self::parseMoneyAttribute(self::getField($totalsElement, 'valueexcl', $invoiceTotals), $invoice->getCurrencyToString()))
                ->setValueInc(self::parseMoneyAttribute(self::getField($totalsElement, 'valueinc', $invoiceTotals), $invoice->getCurrencyToString()));

            // Set the custom class to the invoice
            $invoice->setTotals($invoiceTotals);
        }

        // Get the lines element
        $linesDOMTag = $responseDOM->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            // Loop through each returned lines for the invoice
            foreach ($linesDOMTag->item(0)->childNodes as $lineElement) {
                if ($lineElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary InvoiceLine class
                $invoiceLine = new InvoiceLine();

                // Set the ID attribute
                $invoiceLine->setID($lineElement->getAttribute('id'));

                // Set the invoice line elements from the line element
                $invoiceLine->setAllowDiscountOrPremium(Util::parseBoolean(self::getField($lineElement, 'allowdiscountorpremium', $invoiceLine)))
                    ->setArticle(self::parseObjectAttribute(\PhpTwinfield\Article::class, $invoiceLine, $lineElement, 'article'))
                    ->setDescription(self::getField($lineElement, 'description', $invoiceLine))
                    ->setDim1(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $invoiceLine, $lineElement, 'dim1'))
                    ->setFreeText1(self::getField($lineElement, 'freetext1', $invoiceLine))
                    ->setFreeText2(self::getField($lineElement, 'freetext2', $invoiceLine))
                    ->setFreeText3(self::getField($lineElement, 'freetext3', $invoiceLine))
                    ->setPerformanceDate(self::parseDateAttribute(self::getField($lineElement, 'performancedate', $invoiceLine)))
                    ->setPerformanceType(self::parseEnumAttribute(\PhpTwinfield\Enums\PerformanceType::class, self::getField($lineElement, 'performancetype', $invoiceLine)))
                    ->setQuantity(self::getField($lineElement, 'quantity', $invoiceLine))
                    ->setSubArticleFromString(self::getField($lineElement, 'subarticle', $invoiceLine))
                    ->setUnits(self::getField($lineElement, 'units', $invoiceLine))
                    ->setUnitsPriceExcl(self::parseMoneyAttribute(self::getField($lineElement, 'unitspriceexcl', $invoiceLine), $invoice->getCurrencyToString()))
                    ->setUnitsPriceInc(self::parseMoneyAttribute(self::getField($lineElement, 'unitspriceinc', $invoiceLine), $invoice->getCurrencyToString()))
                    ->setValueExcl(self::parseMoneyAttribute(self::getField($lineElement, 'valueexcl', $invoiceLine), $invoice->getCurrencyToString()))
                    ->setValueInc(self::parseMoneyAttribute(self::getField($lineElement, 'valueinc', $invoiceLine), $invoice->getCurrencyToString()))
                    ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $invoiceLine, $lineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')))
                    ->setVatValue(self::parseMoneyAttribute(self::getField($lineElement, 'vatvalue', $invoiceLine), $invoice->getCurrencyToString()));

                // Set the custom class to the invoice
                $invoice->addLine($invoiceLine);
            }
        }

        // Get the vatlines element
        $vatlinesDOMTag = $responseDOM->getElementsByTagName('vatlines');

        if (isset($vatlinesDOMTag) && $vatlinesDOMTag->length > 0) {
            // Loop through each returned lines for the invoice
            foreach ($vatlinesDOMTag->item(0)->childNodes as $vatlineElement) {
                if ($vatlineElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary InvoiceVatLine class
                $invoiceVatLine = new InvoiceVatLine();

                // Set the invoice vat line elements from the vat line element
                $invoiceVatLine->setPerformanceDate(self::parseDateAttribute(self::getField($vatlineElement, 'performancedate', $invoiceVatLine)))
                    ->setPerformanceType(self::parseEnumAttribute(\PhpTwinfield\Enums\PerformanceType::class, self::getField($vatlineElement, 'performancetype', $invoiceVatLine)))
                    ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $invoiceVatLine, $vatlineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')))
                    ->setVatValue(self::parseMoneyAttribute(self::getField($vatlineElement, 'vatvalue', $invoiceVatLine), $invoice->getCurrencyToString()));

                // Set the custom class to the invoice
                $invoice->addVatLine($invoiceVatLine);
            }
        }

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Set the invoice elements from the financials element
            $invoice->setFinancialCode(self::getField($financialsElement, 'code', $invoice))
                ->setFinancialNumber(self::getField($financialsElement, 'number', $invoice));
        }

        //Return the complete object
        return $invoice;
    }
}