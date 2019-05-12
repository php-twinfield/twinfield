<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\InvoiceVatLine;
use PhpTwinfield\Response\Response;

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
        $invoice->setBank(self::parseObjectAttribute('CashBankBook', $invoice, $invoiceElement, 'bank'))
            ->setCurrency(self::parseObjectAttribute('Currency', $invoice, $invoiceElement, 'currency'))
            ->setCustomer(self::parseObjectAttribute('Customer', $invoice, $invoiceElement, 'customer'))
            ->setDeliverAddressNumber(self::getField($invoice, $invoiceElement, 'deliveraddressnumber'))
            ->setDueDate(self::parseDateAttribute(self::getField($invoice, $invoiceElement, 'duedate')))
            ->setFooterText(self::getField($invoice, $invoiceElement, 'footertext'))
            ->setHeaderText(self::getField($invoice, $invoiceElement, 'headertext'))
            ->setInvoiceAddressNumber(self::getField($invoice, $invoiceElement, 'invoiceaddressnumber'))
            ->setInvoiceDate(self::parseDateAttribute(self::getField($invoice, $invoiceElement, 'invoicedate')))
            ->setInvoiceNumber(self::getField($invoice, $invoiceElement, 'invoicenumber'))
            ->setInvoiceType(self::parseObjectAttribute('InvoiceType', $invoice, $invoiceElement, 'invoicetype'))
            ->setOffice(self::parseObjectAttribute('Office', $invoice, $invoiceElement, 'office'))
            ->setPaymentMethod(self::parseEnumAttribute('PaymentMethod', self::getField($invoice, $invoiceElement, 'paymentmethod')))
            ->setPeriod(self::getField($invoice, $invoiceElement, 'period'))
            ->setPerformanceDate(self::parseDateAttribute(self::getField($invoice, $invoiceElement, 'performancedate')))
            ->setStatus(self::parseEnumAttribute('InvoiceStatus', self::getField($invoice, $invoiceElement, 'status')));

        // Get the totals element
        $totalsElement = $responseDOM->getElementsByTagName('totals')->item(0);

        if ($totalsElement !== null) {
            // Make a new temporary InvoiceTotals class
            $invoiceTotals = new InvoiceTotals();

            // Set the invoice totals elements from the totals element
            $invoiceTotals->setValueExcl(self::parseMoneyAttribute(self::getField($invoiceTotals, $totalsElement, 'valueexcl')))
                ->setValueInc(self::parseMoneyAttribute(self::getField($invoiceTotals, $totalsElement, 'valueinc')));

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
                $invoiceLine->setAllowDiscountOrPremium(self::parseBooleanAttribute(self::getField($invoiceLine, $lineElement, 'allowdiscountorpremium')))
                    ->setArticle(self::parseObjectAttribute('Article', $invoiceLine, $lineElement, 'article'))
                    ->setDescription(self::getField($invoiceLine, $lineElement, 'description'))
                    ->setDim1(self::parseObjectAttribute('GeneralLedger', $invoiceLine, $lineElement, 'dim1'))
                    ->setFreeText1(self::getField($invoiceLine, $lineElement, 'freetext1'))
                    ->setFreeText2(self::getField($invoiceLine, $lineElement, 'freetext2'))
                    ->setFreeText3(self::getField($invoiceLine, $lineElement, 'freetext3'))
                    ->setPerformanceDate(self::parseDateAttribute(self::getField($invoiceLine, $lineElement, 'performancedate')))
                    ->setPerformanceType(self::parseEnumAttribute('PerformanceType', self::getField($invoiceLine, $lineElement, 'performancetype')))
                    ->setQuantity(self::getField($invoiceLine, $lineElement, 'quantity'))
                    ->setSubArticle(self::parseObjectAttribute('ArticleLine', $invoiceLine, $lineElement, 'subarticle'))
                    ->setUnits(self::getField($invoiceLine, $lineElement, 'units'))
                    ->setUnitsPriceExcl(self::parseMoneyAttribute(self::getField($invoiceLine, $lineElement, 'unitspriceexcl')))
                    ->setUnitsPriceInc(self::parseMoneyAttribute(self::getField($invoiceLine, $lineElement, 'unitspriceinc')))
                    ->setValueExcl(self::parseMoneyAttribute(self::getField($invoiceLine, $lineElement, 'valueexcl')))
                    ->setValueInc(self::parseMoneyAttribute(self::getField($invoiceLine, $lineElement, 'valueinc')))
                    ->setVatCode(self::parseObjectAttribute('VatCode', $invoiceLine, $lineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')))
                    ->setVatValue(self::parseMoneyAttribute(self::getField($invoiceLine, $lineElement, 'vatvalue')));

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
                $invoiceVatLine->setPerformanceDate(self::parseDateAttribute(self::getField($invoiceVatLine, $vatlineElement, 'performancedate')))
                    ->setPerformanceType(self::parseEnumAttribute('PerformanceType', self::getField($invoiceVatLine, $vatlineElement, 'performancetype')))
                    ->setVatCode(self::parseObjectAttribute('VatCode', $invoiceVatLine, $vatlineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')))
                    ->setVatValue(self::parseMoneyAttribute(self::getField($invoiceVatLine, $vatlineElement, 'vatvalue')));

                // Set the custom class to the invoice
                $invoice->addVatLine($invoiceVatLine);
            }
        }

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Set the invoice elements from the financials element
            $invoice->setFinancialCode(self::getField($invoice, $financialsElement, 'code'))
                ->setFinancialNumber(self::getField($invoice, $financialsElement, 'number'));
        }

        //Return the complete object
        return $invoice;
    }
}