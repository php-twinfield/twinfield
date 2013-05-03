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
		$codeElement = $this->createElement('code');
		$codeElement->appendChild($this->createTextNode($customer->getID()));
		$this->dimensionElement->appendChild($codeElement);

		// Make name element
		$nameElement = $this->createElement('name');
		$nameElement->appendChild($this->createTextNode($customer->getName()));
		$this->dimensionElement->appendChild($nameElement);

		// Make type element
		$typeElement = $this->createElement('type');
		$typeElement->appendChild($this->createTextNode($customer->getType()));
		$this->dimensionElement->appendChild($typeElement);

		// Make website element
		$websiteElement = $this->createElement('website');
		$websiteElement->appendChild($this->createTextNode($customer->getWebsite()));
		$this->dimensionElement->appendChild($websiteElement);
		
		// Test coc element
		$cocNumberElement = $this->createElement('cocnumber');
		$cocNumberElement->appendChild($this->createTextNode($customer->getCocNumber()));
		$this->dimensionElement->appendChild($cocNumberElement);


		if ( $customer->getDueDays() > 0 ) {
			// Make financial element
			$financialElement = $this->createElement('financials');
			$this->dimensionElement->appendChild($financialElement);

			// Set financial child elements
			$dueDaysElement		 = $this->createElement('duedays');
			$dueDaysElement->appendChild($this->createTextNode($customer->getDueDays()));
			
			$payAvailableElement = $this->createElement('payavailable');
			$payAvailableElement->appendChild($this->createTextNode($customer->getPayAvailable()));
			
			$payCodeElement		 = $this->createElement('paycode');
			$payCodeElement->appendChild($this->createTextNode($customer->getPayCode()));
			
			$vatCodeElement		 = $this->createElement('vatcode');
			$vatCodeElement->appendChild($this->createTextNode($customer->getVatCode()));
			
			$eBillingElement	 = $this->createElement('ebilling');
			$eBillingElement->appendChild($this->createTextNode($customer->getEBilling()));
			
			$eBillMailElement	 = $this->createElement('ebillmail');
			$eBillMailElement->appendChild($this->createTextNode($customer->getEBillMail()));

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
			$aNameElement = $this->createElement('name');
			$aNameElement->appendChild($this->createTextNode($address->getName()));
			
			$countryElement = $this->createElement( 'country');
			$countryElement->appendChild($this->createTextNode($address->getCountry()));
			
			$cityElement = $this->createElement('city');
			$cityElement->appendChild($this->createTextNode($address->getCity()));
			
			$postcodeElement = $this->createElement('postcode');
			$postcodeElement->appendChild($this->createTextNode($address->getPostcode()));
			
			$telephoneElement = $this->createElement('telephone');
			$telephoneElement->appendChild($this->createTextNode($address->getTelephone()));
			
			$faxElement = $this->createElement('telefax');
			$faxElement->appendChild($this->createTextNode($address->getFax()));
			
			$emailElement = $this->createElement('email');
			$emailElement->appendChild($this->createTextNode($address->getEmail()));
			
			$field1Element = $this->createElement('field1');
			$field1Element->appendChild( $this->createTextNode($address->getField1()));
			
			$field2Element = $this->createElement('field2');
			$field2Element->appendChild( $this->createTextNode($address->getField2()));
			
			$field3Element = $this->createElement('field3');
			$field3Element->appendChild( $this->createTextNode($address->getField3()));
			
			$field4Element = $this->createElement('field4');
			$field4Element->appendChild( $this->createTextNode($address->getField4()));
			
			$field5Element = $this->createElement('field5');
			$field5Element->appendChild( $this->createTextNode($address->getField5()));
			
			$field6Element = $this->createElement('field6');
			$field6Element->appendChild( $this->createTextNode($address->getField6()));

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