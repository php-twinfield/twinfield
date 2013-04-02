<?php

namespace Pronamic\Twinfield\Customer;

use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Customer\Mapper\CustomerMapper;
use \Pronamic\Twinfield\Request as TwinfieldRequest;

class CustomerFactory extends ParentFactory {
	public function get($code, $office = null) {
		if($this->getLogin()->process()) {
			$service = $this->getService();

			if(!$office) $office = $this->getConfig()->getOffice();

			$request_customer = new TwinfieldRequest\Read\Customer();
			$request_customer
				->setOffice($office)
				->setCode($code);

			$response = $service->send($request_customer);
			$this->setResponse($response);
			
			if($response->isSuccessful()) {
				return CustomerMapper::map($response);
			}
		}
	}

	public function send(Customer $customer) {
		if($this->getLogin()->process()) {
			$service = $this->getService();

			$customersDocument = new DOM\CustomersDocument();
			$customersDocument->addCustomer($customer);

			$response = $service->send($customersDocument);
			$this->setResponse($response);

			if($response->isSuccessful()) {
				return true;
			} else {
				return false;
			}
		}
	}
}