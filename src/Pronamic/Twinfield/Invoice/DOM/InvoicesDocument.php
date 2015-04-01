<?php
namespace Pronamic\Twinfield\Invoice\DOM;

use \Pronamic\Twinfield\Invoice\Invoice;

/**
 * The Document Holder for making new XML invoices.  Is a child class
 * of DOMDocument and makes the required DOM for the interaction in
 * creating a new invoice.
 *
 * @package Pronamic\Twinfield
 * @subpackage Invoice\DOM
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class InvoicesDocument extends \DOMDocument
{
    /**
     * Holds the <salesinvoice> element
     * that all additional elements should be a child of.
     * @var \DOMElement
     */
    private $salesInvoicesElement;

    /**
     * Creates the <salesinvoice> element and adds it to the property
     * salesInvoicesElement
     * 
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        // Make the main wrap element
        $this->salesInvoicesElement = $this->createElement('salesinvoices');
        $this->appendChild($this->salesInvoicesElement);
    }

    /**
     * Creates a new <salesinvoice> element and assigns it to 
     * this \DOMDocument and returns it.
     * 
     * @access public
     * @return \DOMElement A <salesinvoice> DOMElement
     */
    public function getNewInvoice()
    {
        // Make the new salesinvoice element
        $salesInvoiceElement = $this->createElement('salesinvoice');

        // Add to the main salesinvoices element
        $this->salesInvoicesElement->appendChild($salesInvoiceElement);

        // Return the saleinvoice element
        return $salesInvoiceElement;
    }

    /**
     * Turns a passed Invoice class into the required markup for interacting
     * with Twinfield.
     * 
     * This method doesn't return anything, instead just adds the invoice
     * to this DOMDocument instance for submission usage.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Invoice\Invoice $invoice
     * @return void | [Adds to this instance]
     */
    public function addInvoice(Invoice $invoice)
    {
        // Make a new <salesinvoice> element
        $invoiceElement = $this->getNewInvoice();

        // Makes a child header element
        $headerElement = $this->createElement('header');
        $invoiceElement->appendChild($headerElement);
        
        // Set customer element
        $customer = $invoice->getCustomer();

        // <customer>
        $customerNode    = $this->createTextNode($customer->getCode());
        $customerElement = $this->createElement('customer');
        $customerElement->appendChild($customerNode);
        $headerElement->appendChild($customerElement);

        // Elements and their associated methods for invoice
        $headerTags = array(
            'invoicetype'          => 'getInvoiceType',
            'invoicenumber'        => 'getInvoiceNumber',
            'status'               => 'getStatus',
            'currency'             => 'getCurrency',
            'period'               => 'getPeriod',
            'invoicedate'          => 'getInvoiceDate',
            'duedate'              => 'getDueDate',
            'bank'                 => 'getBank',
            'invoiceaddressnumber' => 'getInvoiceAddressNumber',
            'deliveraddressnumber' => 'getDeliverAddressNumber',
            'headertext'           => 'getHeaderText',
            'footertext'           => 'getFooterText'
        );
        
        // Go through each element and use the assigned method
        foreach ($headerTags as $tag => $method) {
            
            // Make text node for method value
            $node = $this->createTextNode($invoice->$method());
            
            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);
            
            // Add the full element
            $headerElement->appendChild($element);
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
            'performancedate' => 'getPerformanceDate'
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
                $node = $this->createTextNode($line->$method());
                
                // Make the actual element with tag
                $element = $this->createElement($tag);
                $element->appendChild($node);
                
                // Add the full element
                $lineElement->appendChild($element);
            }
        }
    }
}
