<?php

namespace Pronamic\Twinfield\Invoice\Mapper;

use \Pronamic\Twinfield\Invoice\Invoice;
use \Pronamic\Twinfield\Invoice\InvoiceLine;
use \Pronamic\Twinfield\Invoice\InvoiceTotals;

use \Pronamic\Twinfield\Response\Response;
use \Pronamic\Twinfield\Customer\Customer;

class InvoiceMapper {
	public static function map( Response $response ) {
		$responseDOM = $response->getResponseDocument();
		
		$invoiceTags = array(
			'office'				 => 'setOffice',
			'invoicetype'			 => 'setInvoiceType',
			'invoicenumber'			 => 'setInvoiceNumber',
			'status'				 => 'setStatus',
			'currency'				 => 'setCurrency',
			'period'				 => 'setPeriod',
			'invoicedate'			 => 'setInvoiceDate',
			'duedate'				 => 'setDueDate',
			'performancedate'		 => 'setPerformanceDate',
			'paymentmethod'			 => 'setPaymentMethod',
			'bank'					 => 'setBank',
			'invoiceaddressnumber'	 => 'setInvoiceAddressNumber',
			'deliveraddressnumber'	 => 'setDeliverAddressNumber',
			'headertext'			 => 'setHeaderText',
			'footertext'			 => 'setFooterText'
		);
		
		$customerTags = array(
			'customer' => 'setID'
		);
		
		$totalsTags = array(
			'valueexcl'	 => 'setValueExcl',
			'valueinc'	 => 'setValueInc'
		);
		
		// Generate new Invoice
		$invoice = new Invoice();
		
		// Loop through all invoice tags
		foreach($invoiceTags as $tag => $method) {
			$_tag = $responseDOM->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$invoice->$method($_tag->textContent);
		}
		
		// Make a custom, and loop through custom tags
		$customer = new Customer();
		foreach($customerTags as $tag => $method) {
			$_tag = $responseDOM->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$customer->$method($_tag->textContent);
		}
		
		// Make an InvoiceTotals and loop through custom tags
		$invoiceTotals = new InvoiceTotals();
		foreach($totalsTags as $tag => $method) {
			$_tag = $responseDOM->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$invoiceTotals->$method($_tag->textContent);
		}
		
		// Set the custom classes to the invoice
		$invoice->setCustomer( $customer );
		$invoice->setTotals($invoiceTotals);

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
			'unitspriceinc' => 'setUnitsPriceInc',
			'freetext1' => 'setFreeText1',
			'freetext2' => 'setFreeText2',
			'freetext3' => 'setFreeText3',
			'performancedate' => 'setPerformanceDate'
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