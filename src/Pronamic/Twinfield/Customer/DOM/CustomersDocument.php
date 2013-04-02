<?php

namespace Pronamic\Twinfield\Customer\DOM;

use \Pronamic\Twinfield\Customer\Customer;

class CustomersDocument extends \DOMDocument {

	private $dimensionElement;

	public function __construct() {
		parent::__construct();

		$this->dimensionElement = $this->createElement('dimension');
		$this->appendChild($this->dimensionElement);
	}

	public function addCustomer(Customer $customer) {
		// Makes code element
		$codeElement = $this->createElement('code', $customer->getID());
		$this->dimensionElement->appendChild($codeElement);

		// Make name element
		$nameElement = $this->createElement('name', $customer->getName());
		$this->dimensionElement->appendChild($nameElement);

		// Make type element
		$typeElement = $this->createElement('type', $customer->getType());
		$this->dimensionElement->appendChild($typeElement);

		// Make website element
		$websiteElement = $this->createElement('website', $customer->getWebsite());
		$this->dimensionElement->appendChild($websiteElement);


		if ( $customer->getDueDays() > 0 ) {
			// Make financial element
			$financialElement = $this->createElement('financials');
			$this->dimensionElement->appendChild($financialElement);

			// Set financial child elements
			$dueDaysElement		 = $this->createElement('duedays', $customer->getDueDays());
			$payAvailableElement = $this->createElement('payavailable', $customer->getPayAvailable());
			$payCodeElement		 = $this->createElement('paycode', $customer->getPayCode());
			$vatCodeElement		 = $this->createElement('vatcode', $customer->getVatCode());
			$eBillingElement	 = $this->createElement('ebilling', $customer->getEBilling());
			$eBillMailElement	 = $this->createElement('ebillmail', $customer->getEBillMail());

			// Add these to the financial element
			$financialElement->appendChild($dueDaysElement);
			$financialElement->appendChild($payAvailableElement);
			$financialElement->appendChild($payCodeElement);
			$financialElement->appendChild($vatCodeElement);
			$financialElement->appendChild($eBillingElement);
			$financialElement->appendChild($eBillMailElement);
		}


		// Make address element
		$addressesElement = $this->createElement('addresses');
		$this->dimensionElement->appendChild($addressesElement);

		foreach($customer->getAddresses() as $address) {
			// Makes new address element
			$addressElement = $this->createElement('address');
			$addressesElement->appendChild($addressElement);

			// Set attributes
			$addressElement->setAttribute('default', $address->getDefault());
			$addressElement->setAttribute('type', $address->getType());

			// Build elements
			$aNameElement		 = $this->createElement( 'name', $address->getName() );
			$countryElement		 = $this->createElement( 'country', $address->getCountry() );
			$cityElement		 = $this->createElement( 'city', $address->getCity() );
			$postcodeElement	 = $this->createElement( 'postcode', $address->getPostcode() );
			$telephoneElement	 = $this->createElement( 'telephone', $address->getTelephone() );
			$faxElement			 = $this->createElement( 'telefax', $address->getFax() );
			$emailElement		 = $this->createElement( 'email', $address->getEmail() );
			$field1Element		 = $this->createElement( 'field1', $address->getField1() );
			$field2Element		 = $this->createElement( 'field2', $address->getField2() );
			$field3Element		 = $this->createElement( 'field3', $address->getField3() );
			$field4Element		 = $this->createElement( 'field4', $address->getField4() );
			$field5Element		 = $this->createElement( 'field5', $address->getField5() );
			$field6Element		 = $this->createElement( 'field6', $address->getField6() );

			// Add these elements to the address
			$addressElement->appendChild($aNameElement);
			$addressElement->appendChild($countryElement);
			$addressElement->appendChild($cityElement);
			$addressElement->appendChild($postcodeElement);
			$addressElement->appendChild($telephoneElement);
			$addressElement->appendChild($faxElement);
			$addressElement->appendChild($emailElement);
			$addressElement->appendChild($field1Element);
			$addressElement->appendChild($field2Element);
			$addressElement->appendChild($field3Element);
			$addressElement->appendChild($field4Element);
			$addressElement->appendChild($field5Element);
			$addressElement->appendChild($field6Element);
		}
	}
}