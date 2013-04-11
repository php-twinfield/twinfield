<?php

namespace Pronamic\Twinfield\Customer\Mapper;

use \Pronamic\Twinfield\Customer\Customer;
use \Pronamic\Twinfield\Customer\CustomerAddress;
use \Pronamic\Twinfield\Response\Response;

class CustomerMapper {
	public static function map(Response $response) {
		$responseDOM = $response->getResponseDocument();
		
		$customerTags = array(
			'code' => 'setID',
			'uid' => 'setUID',
			'name' => 'setName',
			'inuse' => 'setInUse',
			'behaviour' => 'setBehaviour',
			'touched' => 'setTouched',
			'beginperiod' => 'setBeginPeriod',
			'endperiod' => 'setEndPeriod',
			'endyear' => 'setEndYear',
			'website' => 'setWebsite',
			'cocnumber' => 'setCocNumber',
			'vatnumber' => 'setVatNumber',
			'editdimensionname' => 'setEditDimensionName'
		);
		
		// Generate new customer object
		$customer = new Customer();
		
		// Loop through all the tags
		foreach($customerTags as $tag => $method) {
			$_tag = $responseDOM->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$customer->$method($_tag->textContent);
		} 
		
		$financialTags = array(
			'duedays' => 'setDueDays',
			'payavailable' => 'setPayAvailable',
			'paycode' => 'setPayCode',
			'ebilling' => 'setEBilling',
			'ebillmail' => 'setEBillMail'
		);
		
		// Financial elements
		$financialElement = $responseDOM->getElementsByTagName('financials')->item(0);
		
		foreach($financialTags as $tag => $method) {
			$_tag = $financialElement->getElementsByTagName($tag)->item(0);
			
			if(isset($_tag) && isset($_tag->textContent))
				$customer->$method($_tag->textContent);
		}
		
		// Element tags and their methods for address
		$address_tags = array(
			'name'		 => 'setName',
			'country'	 => 'setCountry',
			'city'		 => 'setCity',
			'postcode'	 => 'setPostcode',
			'telephone'	 => 'setTelephone',
			'telefax'	 => 'setFax',
			'email'		 => 'setEmail',
			'field1'	 => 'setField1',
			'field2'	 => 'setField2',
			'field3'	 => 'setField3',
			'field4'	 => 'setField4',
			'field5'	 => 'setField5',
			'field6'	 => 'setField6',
		);
		
		// Build address entries
		foreach($responseDOM->getElementsByTagName('address') as $addressDOM) {
			// Make a new temp customeraddress class
			$temp_address = new CustomerAddress();
			
			// Set the default properties ( id, type, default )
			$temp_address
					->setID($addressDOM->getAttribute('id'))
					->setType($addressDOM->getAttribute('type'))
					->setDefault($addressDOM->getAttribute('default'));
			
			// Loop through the element tags. Determine if it exists and set it if it does
			foreach($address_tags as $tag => $method) {
				$_tag = $addressDOM->getElementsByTagName($tag)->item(0);
				
				// Check if the tag is set, and its content is set, to prevent DOMNode errors
				if(isset($_tag) && isset($_tag->textContent))
					$temp_address->$method($_tag->textContent);
			}
			
			// Add the address to the customer
			$customer->addAddress($temp_address);
			
			// Clean that memory!
			unset($temp_address);
		}

		return $customer;
	}
}