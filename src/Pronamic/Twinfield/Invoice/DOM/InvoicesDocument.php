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
use \Pronamic\Twinfield\DOM\Document;
use \Pronamic\Twinfield\Invoice\Invoice;

class InvoicesDocument extends Document {

	private $salesInvoicesElement;

	public function __construct() {
		parent::__construct();

		// Set elements to check in the securedocument
		$this->setElementsToCheck( array( 'salesinvoices' => 'result' ) );

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
		$headerElement->appendChild( $customerElement );

		// Set invoicetype element
		$invoiceTypeElement = $this->createElement( 'invoicetype', $invoice->getInvoiceType() );
		$headerElement->appendChild( $invoiceTypeElement );

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
		}
	}
}
