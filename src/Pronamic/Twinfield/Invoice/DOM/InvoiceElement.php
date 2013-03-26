<?php

namespace Pronamic\Twinfield\Invoice\DOM;

use Pronamic\Twinfield\DOM\Element;

class InvoiceElement extends Element {

	public function add( Invoice $invoice ) {
		$document = $this->getDocument();

		$invoiceElement = $document->getNewInvoice();

		// Makes header element
		$headerElement = $document->createElement( 'header' );
		$invoiceElement->appendChild( $headerElement );

		// Set customer element
		$customer = $invoice->getCustomer();
		$customerElement = $document->createElement( 'customer', $customer->getID() );
		$headerElement->appendChild( $customerElement );

		// Set invoicetype element
		$invoiceTypeElement = $document->createElement( 'invoicetype', $invoice->getInvoiceType() );
		$headerElement->appendChild( $invoiceTypeElement );

		// Add orders
		$linesElement = $document->createElement( 'lines' );
		$invoiceElement->appendChild( $linesElement );

		// Loop through all orders, and add those elements
		foreach ( $invoice->getLines() as $line ) {

			// Make a new line element, and add to <lines>
			$lineElement = $document->createElement( 'line' );
			$linesElement->appendChild( $lineElement );

			// Set attributes
			$quantityElement		 = $document->createElement( 'quantity', $line->getQuantity() );
			$articleElement			 = $document->createElement( 'article', $line->getArticle() );
			$subarticleElement		 = $document->createElement( 'subarticle', $line->getSubArticle() );
			$descriptionElement		 = $document->createElement( 'description', $line->getDescription() );
			$unitsPriceExclElement	 = $document->createElement( 'unitspriceexcl', $line->getUnitsPriceExcl() );
			$unitsElement			 = $document->createElement( 'units', $line->getUnits() );
			$vatCodeElement			 = $document->createElement( 'vatcode', $line->getVatCode() );
			$freeText1Element		 = $document->createElement( 'freetext1', $line->getFreeText1() );
			$freeText2Element		 = $document->createElement( 'freetext2', $line->getFreeText2() );

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