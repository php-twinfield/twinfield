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
				->setID($responseDOM->first('code'))
				->setUID($responseDOM->first('uid'))
				->setName($responseDOM->first('name'))
				->setInUse($responseDOM->first('inuse'))
				->setBehaviour($responseDOM->first('behaviour'))
				->setTouched($responseDOM->first('touched'))
				->setBeginPeriod($responseDOM->first('beginperiod'))
				->setBeginYear($responseDOM->first('beginyear'))
				->setEndPeriod($responseDOM->first('endperiod'))
				->setEndYear($responseDOM->first('endyear'))
				->setWebsite($responseDOM->first('website'))
				->setCocNumber($responseDOM->first('cocnumber'))
				->setVatNumber($responseDOM->first('vatnumber'))
				->setEditDimensionName($responseDOM->first('editdimensionname'));

		// Financial elements
		$financialElement = $responseDOM->getElementsByTagName('financials')->item(0);

		$customer
				->setDueDays($financialElement->first('duedays'))
				->setPayAvailable($financialElement->first('payavailable'))
				->setPayCode($financialElement->first('paycode'))
				->setEBilling($financialElement->first('ebilling'))
				->setEBillMail($financialElement->first('ebillmail'));

		// Build address entries
		foreach($responseDOM->first('address') as $addressDOM) {
			$temp_address = new CustomerAddress();
			$temp_address
					->setID($addressDOM->getAttribute('id'))
					->setType($addressDOM->getAttribute('type'))
					->setDefault($addressDOM->getAttribute('default'))
					->setName($addressDOM->first('name'))
					->setCountry($addressDOM->first('country'))
					->setCity($addressDOM->first('city'))
					->setPostcode($addressDOM->first('postcode'))
					->setTelephone($addressDOM->first('telephone'))
					->setFax($addressDOM->first('telefax'))
					->setEmail($addressDOM->first('email'))
					->setField1($addressDOM->first('field1'))
					->setField2($addressDOM->first('field2'))
					->setField3($addressDOM->first('field3'))
					->setField4($addressDOM->first('field4'))
					->setField5($addressDOM->first('field5'))
					->setField6($addressDOM->first('field6'));

			$customer->addAddress($temp_address);
			unset($temp_address);

		}

		return $customer;

	}
}