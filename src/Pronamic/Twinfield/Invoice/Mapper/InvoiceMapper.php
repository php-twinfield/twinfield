<?php

namespace Pronamic\Twinfield\Invoice\Mapper;

use \Pronamic\Twinfield\Invoice\Invoice;
use \Pronamic\Twinfield\Invoice\InvoiceLine;

use \Pronamic\Twinfield\Response\Response;
use \Pronamic\Twinfield\Customer\Customer;

class InvoiceMapper {
	public static function map( Response $response ) {
		$responseDOM = $response->getResponseDocument();
		
		$invoiceTags = array(
			'office' => 'setOffice',
			'invoicetype' => 'setInvoiceType',
			'invoicenumber' => 'setInvoiceNumber',
			'invoicedate' => 'setInvoiceDate',
			'duedate' => 'setDueDate',
			'bank' => 'setBank',
			'invoiceaddressnumber' => 'setInvoiceAddressNumber',
			'deliveraddressnumber' => 'setDeliverAddressNumber',
			'period' => 'setPeriod',
			'currency' => 'setCurrency',
			'status' => 'setStatus',
			'paymentmethod' => 'setPaymentMethod'
		);
		
		$customerTags = array(
			'customer' => 'setID'
		);
		
		// Generate new Invoice
		$invoice = new Invoice();
		
		foreach($invoiceTags as $tag => $method) {
			$_tag = $responseDOM->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$invoice->$method($_tag->textContent);
		}
		
		$customer = new Customer();
		
		foreach($customerTags as $tag => $method) {
			$_tag = $responseDOM->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$customer->$method($_tag->textContent);
		}
		
		$invoice->setCustomer( $customer );

		$lineTags = array(
			'article' => 'setArticle',
			'subarticle' => 'setSubArticle',
			'quantity' => 'setQuantity',
			'units' => 'setUnits',
			'allowdiscountorpremium' => 'setAllowDiscountOrPremium',
			'description' => 'setDescription',
			'valueexcl' => 'setValueExcl',
			'vatvalue' => 'setVatValue',
			'valueinc' => 'setValueInc',
			'unitspriceexcl' => 'setUnitsPriceExcl',
			'freetext1' => 'setFreeText1',
			'freetext2' => 'setFreeText2',
			'freetext3' => 'setFreeText3'
		);
		
		foreach ( $responseDOM->getElementsByTagName( 'line' ) as $lineDOM ) {
			$temp_line = new InvoiceLine();
			
			foreach($lineTags as $tag => $method) {
				$_tag = $lineDOM->getElementsByTagName($tag)->item(0);
				
				if(isset($_tag) && isset($_tag->textContent))
					$temp_line->$method($_tag->textContent);
			}
			
			$invoice->addLine( $temp_line );
			unset( $temp_line );
		}

		return $invoice;
	}
}