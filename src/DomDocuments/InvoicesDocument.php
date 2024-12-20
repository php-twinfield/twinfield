<?php
namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Invoice;

/**
 * The Document Holder for making new XML invoices.  Is a child class
 * of DOMDocument and makes the required DOM for the interaction in
 * creating a new invoice.
 *
 * @package PhpTwinfield
 * @subpackage Invoice\DOM
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class InvoicesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "salesinvoices";
    }

    /**
     * Turns a passed Invoice class into the required markup for interacting
     * with Twinfield.
     * 
     * This method doesn't return anything, instead just adds the invoice
     * to this DOMDocument instance for submission usage.
     * 
     * @access public
     * @param Invoice $invoice
     * @return void | [Adds to this instance]
     */
    public function addInvoice(Invoice $invoice)
    {
        // Make a new <salesinvoice> element
        $invoiceElement = $this->createElement('salesinvoice');
        if ($invoice->getRaiseWarning() !== null) {
            $invoiceElement->appendChild($this->createBooleanAttribute('raisewarning', $invoice->getRaiseWarning()));
        }

        // Makes a child header element
        $headerElement = $this->createElement('header');
        $invoiceElement->appendChild($headerElement);
        
        // Set customer element
        $customer = $invoice->getCustomer();

        // <customer>
        $customerNode    = $this->createTextNode($customer->getCode()  ?? '');
        $customerElement = $this->createElement('customer');
        $customerElement->appendChild($customerNode);
        $headerElement->appendChild($customerElement);

        // Elements and their associated methods for invoice
        $headerTags = array(
            'office'               => 'getOffice',
            'invoicetype'          => 'getInvoiceType',
            'invoicenumber'        => 'getInvoiceNumber',
            'status'               => 'getStatus',
            'currency'             => 'getCurrency',
            'period'               => 'getPeriod',
            'invoicedate'          => 'getInvoiceDate',
            'duedate'              => 'getDueDate',
            'paymentmethod'        => 'getPaymentMethod',
            'bank'                 => 'getBank',
            'invoiceaddressnumber' => 'getInvoiceAddressNumber',
            'deliveraddressnumber' => 'getDeliverAddressNumber',
            'headertext'           => 'getHeaderText',
            'footertext'           => 'getFooterText'
        );
        
        // Go through each element and use the assigned method
        foreach ($headerTags as $tag => $method) {

            $value = $this->getValueFromCallback([$invoice, $method]);
    
            if(null !== $value) {
                // Make text node for method value
                $node = $this->createTextNode($value  ?? '');
    
                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);
    
                // Add the full element
                $headerElement->appendChild($element);
            }
        }

        // Add orders
        $linesElement = $this->createElement('lines');
        $invoiceElement->appendChild($linesElement);
        
        // Elements and their associated methods for lines
        $lineTags = array(
            'quantity'        => 'getQuantity',
            'article'         => 'getArticle',
            'subarticle'      => 'getSubArticle',
            'description'     => 'getDescription',
            'unitspriceexcl'  => 'getUnitsPriceExcl',
            'units'           => 'getUnits',
            'vatcode'         => 'getVatCode',
            'freetext1'       => 'getFreeText1',
            'freetext2'       => 'getFreeText2',
            'freetext3'       => 'getFreeText3',
            'performancedate' => 'getPerformanceDate',
            'performancetype' => 'getPerformanceType',
            'dim1'            => 'getDim1',
        );

        // Loop through all orders, and add those elements
        foreach ($invoice->getLines() as $line) {

            // Make a new line element, and add to <lines>
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('id', $line->getID());
            $linesElement->appendChild($lineElement);

            // Go through each element and use the assigned method
            foreach ($lineTags as $tag => $method) {
                
                // Make text node for method value
                $node = $this->createTextNode($this->getValueFromCallback([$line, $method])  ?? '');

                if ($node->textContent === "") {
                    continue;
                }

                // Make the actual element with tag
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $lineElement->appendChild($element);
            }
        }

        $this->rootElement->appendChild($invoiceElement);
    }
}
