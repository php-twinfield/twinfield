<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Customer;
use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceVatLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\Response\Response;

class InvoiceMapper extends BaseMapper
{
    public static function map(Response $response)
    {
        $responseDOM = $response->getResponseDocument();
        $invoiceElement = $responseDOM->documentElement;

        // Generate new Invoice
        $invoice = new Invoice();
        $invoice->setResult($invoiceElement->getAttribute('result'));

        $invoiceTags = array(
            'bank'                 => 'setBank',
            'currency'             => 'setCurrency',
            'deliveraddressnumber' => 'setDeliverAddressNumber',
            'duedate'              => 'setDueDate',
            'footertext'           => 'setFooterText',
            'headertext'           => 'setHeaderText',
            'invoiceaddressnumber' => 'setInvoiceAddressNumber',
            'invoicedate'          => 'setInvoiceDate',
            'invoicenumber'        => 'setInvoiceNumber',
            'invoicetype'          => 'setInvoiceType',
            'office'               => 'setOffice',
            'paymentmethod'        => 'setPaymentMethod',
            'performancedate'      => 'setPerformanceDate',
            'period'               => 'setPeriod',
            'status'               => 'setStatus',
        );

        // Loop through all invoice tags
        foreach ($invoiceTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$invoice, $method]);
        }

        // Make a customer, and loop through custom tags
        $customerTags = array(
            'customer' => 'setCode',
        );

        $customer = new Customer();
        foreach ($customerTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            if (isset($_tag) && isset($_tag->textContent)) {
                $customer->$method($_tag->textContent);
            }
        }

        // Set the custom class to the invoice
        $invoice->setCustomer($customer);

        // Make an InvoiceTotals and loop through custom tags
        $totalsTags = array(
            'valueexcl' => 'setValueExcl',
            'valueinc'  => 'setValueInc',
        );

        $invoiceTotals = new InvoiceTotals();

        $totalElement = $responseDOM->getElementsByTagName('totals')->item(0);

        if ($totalElement !== null) {
            // Go through each total element and add to the assigned method
            foreach ($totalsTags as $tag => $method) {
                $invoiceTotals->$method(self::getField($totalElement, $tag));
            }
        }

        // Set the custom class to the invoice
        $invoice->setTotals($invoiceTotals);

        $linesDOMTag = $responseDOM->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            // Element tags and their methods for lines
            $lineTags = array(
                'allowdiscountorpremium' => 'setAllowDiscountOrPremium',
                'article'                => 'setArticle',
                'description'            => 'setDescription',
                'dim1'                   => 'setDim1',
                'freetext1'              => 'setFreeText1',
                'freetext2'              => 'setFreeText2',
                'freetext3'              => 'setFreeText3',
                'performancedate'        => 'setPerformanceDate',
                'performancetype'        => 'setPerformanceType',
                'quantity'               => 'setQuantity',
                'subarticle'             => 'setSubArticle',
                'units'                  => 'setUnits',
                'unitspriceexcl'         => 'setUnitsPriceExcl',
                'unitspriceinc'          => 'setUnitsPriceInc',
                'valueexcl'              => 'setValueExcl',
                'valueinc'               => 'setValueInc',
                'vatcode'                => 'setVatCode',
                'vatvalue'               => 'setVatValue',
            );

            $linesDOM = $linesDOMTag->item(0);

            // Loop through each returned lines for the invoice
            foreach ($linesDOM->childNodes as $lineDOM) {
                if ($lineDOM->nodeType !== 1) {
                    continue;
                }

                $invoiceLine = new InvoiceLine();
                $invoiceLine->setID($lineDOM->getAttribute('id'));

                foreach ($lineTags as $tag => $method) {

                    $content = self::getField($lineDOM, $tag);

                    if (null !== $content) {
                        $invoiceLine->$method($content);
                    }
                }

                $invoice->addLine($invoiceLine);
            }
        }

        $vatlinesDOMTag = $responseDOM->getElementsByTagName('vatlines');

        if (isset($vatlinesDOMTag) && $vatlinesDOMTag->length > 0) {
            // Element tags and their methods for vatlines
             $vatlineTags = array(
                'performancedate'        => 'setPerformanceDate',
                'performancetype'        => 'setPerformanceType',
                'vatcode'                => 'setVatCode',
                'vatvalue'               => 'setVatValue',
            );

            $vatlinesDOM = $vatlinesDOMTag->item(0);

            // Loop through each returned lines for the invoice
            foreach ($vatlinesDOM->childNodes as $vatlineDOM) {
                if ($vatlineDOM->nodeType !== 1) {
                    continue;
                }

                $invoiceVatLine = new InvoiceVatLine();

                foreach ($vatlineTags as $tag => $method) {

                    $content = self::getField($vatlineDOM, $tag);

                    if (null !== $content) {
                        $invoiceVatLine->$method($content);
                    }
                }

                $invoice->addVatLine($invoiceVatLine);
            }
        }

        // Financial elements and their methods
        $financialsTags = array(
            'code'      => 'setFinancialCode',
            'number'    => 'setFinancialNumber'
        );

        // Financial elements
        $financialElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialElement !== null) {

            // Go through each financial element and add to the assigned method
            foreach ($financialsTags as $tag => $method) {
                $invoice->$method(self::getField($financialElement, $tag));
            }
        }

        return $invoice;
    }
}