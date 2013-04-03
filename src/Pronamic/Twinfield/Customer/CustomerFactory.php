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

	public function listAll() {
		if($this->getLogin()->process()) {
			$service = $this->getService();

			$request_customers = new TwinfieldRequest\Catalog\Dimension( $this->getConfig()->getOffice() );
			$request_customers->setDimType('DEB');

			$response = $service->send($request_customers);
			$this->setResponse($response);

			if ($response->isSuccessful()) {

				$responseDOM = $response->getResponseDocument();

				$customers = array();

				foreach($responseDOM->getElementsByTagName('dimension') as $customer) {
					$customer_id = $customer->textContent;

					if(!is_numeric($customer_id)) continue;

					$customers[$customer->textContent] = array(
						'name' => $customer->getAttribute('name'),
						'shortName' => $customer->getAttribute('shortname')
					);
				}

				return $customers;
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