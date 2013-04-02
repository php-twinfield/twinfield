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
			$field1Element = $this->createElement('field1', $address->getField1());
			$field2Element = $this->createElement('field2', $address->getField2());
			$field3Element = $this->createElement('field3', $address->getField3());
			$postcodeElement = $this->createElement('postcode', $address->getPostcode());
			$cityElement = $this->createElement('city', $address->getCity());
			$countryElement = $this->createElement('country', $address->getCountry());
			$telephoneElement = $this->createElement('telephone', $address->getTelephone());
			$faxElement = $this->createElement('telefax', $address->getFax());
			$field4Element = $this->createElement('field4', $address->getField4());
			$emailElement = $this->createElement('email', $address->getEmail());

			// Add these elements to the address
			$addressElement->appendChild($field1Element);
			$addressElement->appendChild($field2Element);
			$addressElement->appendChild($field3Element);
			$addressElement->appendChild($postcodeElement);
			$addressElement->appendChild($cityElement);
			$addressElement->appendChild($countryElement);
			$addressElement->appendChild($telephoneElement);
			$addressElement->appendChild($faxElement);
			$addressElement->appendChild($field4Element);
			$addressElement->appendChild($emailElement);
		}
	}
}