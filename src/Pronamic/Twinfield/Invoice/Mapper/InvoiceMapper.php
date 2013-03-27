<?php

namespace Pronamic\Twinfield\Invoice\Mapper;

use \Pronamic\Twinfield\Invoice\Invoice;
use \Pronamic\Twinfield\Invoice\InvoiceLine;

use \Pronamic\Twinfield\Response\Response;
use \Pronamic\Twinfield\Customer\Customer;

class InvoiceMapper {
	public static function map( Response $response ) {

		$responseDOM = $response->getResponseDocument();

		// Generate new Invoice
		$invoice = new Invoice();
		$customer = new Customer();
		$customer->setID( $responseDOM->getElementsByTagName( 'customer' )->item(0)->textContent );

		// Set the invoice properties
		$invoice->setOffice( $responseDOM->getElementsByTagName( 'office' )->item(0)->textContent )
				->setInvoiceType( $responseDOM->getElementsByTagName( 'invoicetype' )->item(0)->textContent )
				->setInvoiceNumber( $responseDOM->getElementsByTagName( 'invoicenumber' )->item(0)->textContent )
				->setInvoiceDate( $responseDOM->getElementsByTagName( 'invoicedate' )->item(0)->textContent )
				->setDueDate( $responseDOM->getElementsByTagName( 'duedate' )->item(0)->textContent )
				->setBank( $responseDOM->getElementsByTagName( 'bank' )->item(0)->textContent )
				->setInvoiceAddressNumber( $responseDOM->getElementsByTagName( 'invoiceaddressnumber' )->item(0)->textContent )
				->setDeliverAddressNumber( $responseDOM->getElementsByTagName( 'deliveraddressnumber' )->item(0)->textContent )
				->setCustomer( $customer )
				->setPeriod( $responseDOM->getElementsByTagName( 'period' )->item(0)->textContent )
				->setCurrency( $responseDOM->getElementsByTagName( 'currency' )->item(0)->textContent )
				->setStatus( $responseDOM->getElementsByTagName( 'status' )->item(0)->textContent )
				->setPaymentMethod( $responseDOM->getElementsByTagName( 'paymentmethod' )->item(0)->textContent );

		foreach ( $responseDOM->getElementsByTagName( 'line' ) as $lineDOM ) {
			$temp_line = new InvoiceLine();
			$temp_line
					->setArticle( $lineDOM->getElementsByTagName( 'article' )->item(0)->textContent )
					->setSubArticle( $lineDOM->getElementsByTagName( 'subarticle' )->item(0)->textContent )
					->setQuantity( $lineDOM->getElementsByTagName( 'quantity' )->item(0)->textContent )
					->setUnits( $lineDOM->getElementsByTagName( 'units' )->item(0)->textContent )
					->setAllowDiscountOrPremium( $lineDOM->getElementsByTagName( 'allowdiscountorpremium' )->item(0)->textContent )
					->setDescription( $lineDOM->getElementsByTagName( 'description' )->item(0)->textContent )
					->setValueExcl( $lineDOM->getElementsByTagName( 'valueexcl' )->item(0)->textContent )
					->setVatValue( $lineDOM->getElementsByTagName( 'vatvalue' )->item(0)->textContent )
					->setValueInc( $lineDOM->getElementsByTagName( 'valueinc' )->item(0)->textContent )
					->setUnitsPriceExcl( $lineDOM->getElementsByTagName( 'unitspriceexcl' )->item(0)->textContent );

			$invoice->addLine( $temp_line );
			unset( $temp_line );
		}

		return $invoice;






	}
}