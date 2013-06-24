<?php

namespace Pronamic\Twinfield\Invoice\DOM;

/**
 * The Document Holder for making new XML invoices.
 * Is an abstract class, and you don't call it directly.
 *
 * It is instead, extended by the Elements class for
 * each component.
 *
 * @uses \Pronamic\Twinfield\DOM\Document Is required to extend as part of the checking methods
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Invoice
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \Pronamic\Twinfield\Invoice\Invoice;

class InvoicesDocument extends \DOMDocument {

	private $salesInvoicesElement;

	public function __construct() {
		parent::__construct();

		// Make the main wrap element
		$this->salesInvoicesElement = $this->createElement( 'salesinvoices' );
		$this->appendChild($this->salesInvoicesElement);
	}

	/**
	 * Creates the new \DOMElement for a salesinvoice,
	 * assigns it to this \DOMDocument and returns it.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \DOMElement A <salesinvoice> DOMElement
	 */
	public function getNewInvoice() {
		// Make the new salesinvoice element
		$salesInvoiceElement = $this->createElement('salesinvoice');

		// Add to the main salesinvoices element
		$this->salesInvoicesElement->appendChild($salesInvoiceElement);

		// Return the saleinvoice element
		return $salesInvoiceElement;
	}

	public function addInvoice(Invoice $invoice) {
		$invoiceElement = $this->getNewInvoice();

		// Makes header element
		$headerElement = $this->createElement( 'header' );
		$invoiceElement->appendChild( $headerElement );

		// Set customer element
		$customer = $invoice->getCustomer();
		
		$customerElement = $this->createElement( 'customer', $customer->getID() );
		$invoiceTypeElement = $this->createElement( 'invoicetype', $invoice->getInvoiceType() );
		$invoiceNumberElement = $this->createElement( 'invoicenumber', $invoice->getInvoiceNumber() );
		$statusElement = $this->createElement( 'status', $invoice->getStatus() );
		$currencyElement = $this->createElement( 'currency', $invoice->getCurrency() );
		$periodElement = $this->createElement( 'period', $invoice->getPeriod() );
		$invoiceDateElement = $this->createElement( 'invoicedate', $invoice->getInvoiceDate() );
		$dueDateElement = $this->createElement( 'duedate', $invoice->getDueDate() );
		$bankElement = $this->createElement( 'bank', $invoice->getBank() );
		$invoiceAddressNumber = $this->createElement( 'invoiceaddressnumber', $invoice->getInvoiceAddressNumber() );
		$deliverAddressNumber = $this->createElement( 'deliveraddressnumber', $invoice->getDeliverAddressNumber() );
		$headerText = $this->createElement( 'headertext', $invoice->getHeaderText() );
		$footerText = $this->createElement( 'footertext', $invoice->getFooterText() );
		
		$headerElement->appendChild( $customerElement );
		$headerElement->appendChild( $invoiceTypeElement );
		$headerElement->appendChild( $invoiceNumberElement );
		$headerElement->appendChild( $statusElement );
		$headerElement->appendChild( $currencyElement );
		$headerElement->appendChild( $periodElement );
		$headerElement->appendChild( $invoiceDateElement );
		$headerElement->appendChild( $dueDateElement );
		$headerElement->appendChild( $bankElement );
		$headerElement->appendChild( $invoiceAddressNumber );
		$headerElement->appendChild( $deliverAddressNumber );
		$headerElement->appendChild( $headerText );
		$headerElement->appendChild( $footerText );
		
		// Add orders
		$linesElement = $this->createElement( 'lines' );
		$invoiceElement->appendChild( $linesElement );

		// Loop through all orders, and add those elements
		foreach ( $invoice->getLines() as $line ) {

			// Make a new line element, and add to <lines>
			$lineElement = $this->createElement( 'line' );
			$linesElement->appendChild( $lineElement );

			// Set attributes
			$quantityElement		 = $this->createElement( 'quantity', $line->getQuantity() );
			$articleElement			 = $this->createElement( 'article', $line->getArticle() );
			$subarticleElement		 = $this->createElement( 'subarticle', $line->getSubArticle() );
			$descriptionElement		 = $this->createElement( 'description', $line->getDescription() );
			$unitsPriceExclElement	 = $this->createElement( 'unitspriceexcl', $line->getUnitsPriceExcl() );
			$unitsElement			 = $this->createElement( 'units', $line->getUnits() );
			$vatCodeElement			 = $this->createElement( 'vatcode', $line->getVatCode() );
			$freeText1Element		 = $this->createElement( 'freetext1', $line->getFreeText1() );
			$freeText2Element		 = $this->createElement( 'freetext2', $line->getFreeText2() );
			$freeText3Element		 = $this->createElement( 'freetext3', $line->getFreeText3() );
			$performanceDateElement  = $this->createElement( 'performancedate', $line->getPerformanceDate() );

			// Add those attributes to the line
			$lineElement->appendChild( $quantityElement );
			$lineElement->appendChild( $articleElement );
			$lineElement->appendChild( $subarticleElement );
			$lineElement->appendChild( $descriptionElement );
			$lineElement->appendChild( $unitsPriceExclElement );
			$lineElement->appendChild( $unitsElement );
			$lineElement->appendChild( $vatCodeElement );
			$lineElement->appendChild( $freeText1Element );
			$lineElement->appendChild( $freeText2Element );
			$lineElement->appendChild( $freeText3Element );
			$lineElement->appendChild( $performanceDateElement );
		}
	}
}
