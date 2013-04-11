<?php

namespace Pronamic\Twinfield\Customer\Mapper;

use \Pronamic\Twinfield\Customer\Customer;
use \Pronamic\Twinfield\Customer\CustomerAddress;
use \Pronamic\Twinfield\Response\Response;

class CustomerMapper {
	public static function map(Response $response) {
		$responseDOM = $response->getResponseDocument();
		
		// Generate new customer object
		$customer = new Customer();

		$customer
				->setID($responseDOM->getElementsByTagName('code')->item(0)->textContent)
				->setUID($responseDOM->getElementsByTagName('uid')->item(0)->textContent)
				->setName($responseDOM->getElementsByTagName('name')->item(0)->textContent)
				->setInUse($responseDOM->getElementsByTagName('inuse')->item(0)->textContent)
				->setBehaviour($responseDOM->getElementsByTagName('behaviour')->item(0)->textContent)
				->setTouched($responseDOM->getElementsByTagName('touched')->item(0)->textContent)
				->setBeginPeriod($responseDOM->getElementsByTagName('beginperiod')->item(0)->textContent)
				->setBeginYear($responseDOM->getElementsByTagName('beginyear')->item(0)->textContent)
				->setEndPeriod($responseDOM->getElementsByTagName('endperiod')->item(0)->textContent)
				->setEndYear($responseDOM->getElementsByTagName('endyear')->item(0)->textContent)
				->setWebsite($responseDOM->getElementsByTagName('website')->item(0)->textContent)
				->setCocNumber($responseDOM->getElementsByTagName('cocnumber')->item(0)->textContent)
				->setVatNumber($responseDOM->getElementsByTagName('vatnumber')->item(0)->textContent)
				->setEditDimensionName($responseDOM->getElementsByTagName('editdimensionname')->item(0)->textContent);

		// Financial elements
		$financialElement = $responseDOM->getElementsByTagName('financials')->item(0);

		$customer
				->setDueDays($financialElement->getElementsByTagName('duedays')->item(0)->textContent)
				->setPayAvailable($financialElement->getElementsByTagName('payavailable')->item(0)->textContent)
				->setPayCode($financialElement->getElementsByTagName('paycode')->item(0)->textContent)
				->setEBilling($financialElement->getElementsByTagName('ebilling')->item(0)->textContent)
				->setEBillMail($financialElement->getElementsByTagName('ebillmail')->item(0)->textContent);

		// Build address entries
		foreach($responseDOM->getElementsByTagName('address') as $addressDOM) {
			$temp_address = new CustomerAddress();
			$temp_address
					->setID($addressDOM->getAttribute('id'))
					->setType($addressDOM->getAttribute('type'))
					->setDefault($addressDOM->getAttribute('default'))
					->setName($addressDOM->getElementsByTagName('name')->item(0)->textContent)
					->setCountry($addressDOM->getElementsByTagName('country')->item(0)->textContent)
					->setCity($addressDOM->getElementsByTagName('city')->item(0)->textContent)
					->setPostcode($addressDOM->getElementsByTagName('postcode')->item(0)->textContent)
					->setTelephone($addressDOM->getElementsByTagName('telephone')->item(0)->textContent)
					->setFax($addressDOM->getElementsByTagName('telefax')->item(0)->textContent)
					->setEmail($addressDOM->getElementsByTagName('email')->item(0)->textContent)
					->setField1($addressDOM->getElementsByTagName('field1')->item(0)->textContent)
					->setField2($addressDOM->getElementsByTagName('field2')->item(0)->textContent)
					->setField3($addressDOM->getElementsByTagName('field3')->item(0)->textContent)
					->setField4($addressDOM->getElementsByTagName('field4')->item(0)->textContent)
					->setField5($addressDOM->getElementsByTagName('field5')->item(0)->textContent)
					->setField6($addressDOM->getElementsByTagName('field6')->item(0)->textContent);

			$customer->addAddress($temp_address);
			unset($temp_address);

		}

		return $customer;

	}
}