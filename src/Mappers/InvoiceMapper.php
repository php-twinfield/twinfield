<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Customer;

class InvoiceMapper extends BaseMapper
{
    public static function map(Response $response): Invoice
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
            'footertext'           => 'setFooterText'
        );

        $customerTags = array(
            'customer' => 'setCode'
        );

        $totalsTags = array(
            'valueexcl' => 'setValueExcl',
            'valueinc'  => 'setValueInc'
        );

        // Generate new Invoice
        $invoice = new Invoice();

        // Loop through all invoice tags
        foreach ($invoiceTags as $tag => $method) {

            self::setFromTagUsingCallable($responseDOM, $tag, [$invoice, $method]);

        }

        // Make a custom, and loop through custom tags
        $customer = new Customer();
        foreach ($customerTags as $tag => $method) {
            self::setFromTagUsingCallable($responseDOM, $tag, [$customer, $method]);
        }

        // Make an InvoiceTotals and loop through custom tags
        $invoiceTotals = new InvoiceTotals();
        foreach ($totalsTags as $tag => $method) {
            self::setFromTagUsingCallable($responseDOM, $tag, [$invoiceTotals, $method]);
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
        );

        foreach ($responseDOM->getElementsByTagName('line') as $lineDOM) {

            $temp_line = new InvoiceLine();
            $temp_line->setID($lineDOM->getAttribute('id'));

            foreach ($lineTags as $tag => $method) {
                self::setFromTagUsingCallable($lineDOM, $tag, [$temp_line, $method]);
            }

            $invoice->addLine($temp_line);
        }

        return $invoice;
    }
}
