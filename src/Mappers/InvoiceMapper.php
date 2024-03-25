<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Customer;
use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\Response\Response;

class InvoiceMapper extends BaseMapper
{
    public static function map(Response $response)
    {
        $responseDOM = $response->getResponseDocument();

        $invoiceTags = array(
            'office'               => 'setOffice',
            'invoicetype'          => 'setInvoiceType',
            'invoicenumber'        => 'setInvoiceNumber',
            'status'               => 'setStatus',
            'currency'             => 'setCurrency',
            'period'               => 'setPeriod',
            'invoicedate'          => 'setInvoiceDate',
            'duedate'              => 'setDueDateFromString',
            'performancedate'      => 'setPerformanceDate',
            'paymentmethod'        => 'setPaymentMethod',
            'bank'                 => 'setBank',
            'invoiceaddressnumber' => 'setInvoiceAddressNumber',
            'deliveraddressnumber' => 'setDeliverAddressNumber',
            'headertext'           => 'setHeaderText',
            'footertext'           => 'setFooterText',
        );

        $customerTags = array(
            'customer' => 'setCode',
        );

        $totalsTags = array(
            'valueexcl' => 'setValueExcl',
            'valueinc'  => 'setValueInc',
        );

        // Generate new Invoice
        $invoice = new Invoice();

        // Loop through all invoice tags
        foreach ($invoiceTags as $tag => $method) {

            self::setFromTagValue($responseDOM, $tag, [$invoice, $method]);
        }

        // Make a custom, and loop through custom tags
        $customer = new Customer();
        foreach ($customerTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            if (isset($_tag) && isset($_tag->textContent)) {
                $customer->$method($_tag->textContent);
            }
        }

        // Make an InvoiceTotals and loop through custom tags
        $invoiceTotals = new InvoiceTotals();
        foreach ($totalsTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            if (isset($_tag) && isset($_tag->textContent)) {
                $invoiceTotals->$method($_tag->textContent);
            }
        }

        // Set the custom classes to the invoice
        $invoice->setCustomer($customer);
        $invoice->setTotals($invoiceTotals);

        $lineTags = array(
            'article'                => 'setArticle',
            'subarticle'             => 'setSubArticle',
            'quantity'               => 'setQuantity',
            'units'                  => 'setUnits',
            'allowdiscountorpremium' => 'setAllowDiscountOrPremium',
            'description'            => 'setDescription',
            'valueexcl'              => 'setValueExcl',
            'vatvalue'               => 'setVatValue',
            'valueinc'               => 'setValueInc',
            'unitspriceexcl'         => 'setUnitsPriceExcl',
            'unitspriceinc'          => 'setUnitsPriceInc',
            'freetext1'              => 'setFreeText1',
            'freetext2'              => 'setFreeText2',
            'freetext3'              => 'setFreeText3',
            'performancedate'        => 'setPerformanceDate',
            'performancetype'        => 'setPerformanceType',
            'dim1'                   => 'setDim1',
            'vatcode'                => 'setVatCode',
        );

        /** @var \DOMElement $lineDOM */
        foreach ($responseDOM->getElementsByTagName('line') as $lineDOM) {

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
