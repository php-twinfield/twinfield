<?php

namespace Pronamic\Twinfield\Invoice;

use Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Invoice\Mapper\InvoiceMapper;
use \Pronamic\Twinfield\Request as TwinfieldRequest;


class InvoiceFactory extends ParentFactory {

	public function get($code, $invoiceNumber, $office = null) {

		if($this->getLogin()->process()) {

			$service = $this->getService();

			if(!$office) $office = $this->getConfig()->getOffice();

			$request_invoice = new TwinfieldRequest\Read\Invoice();
			$request_invoice
					->setCode($code)
					->setNumber($invoiceNumber)
					->setOffice($office);

			$response = $service->send($request_invoice);
			$this->setResponse($response);

			if($response->isSuccessful()) {
				return InvoiceMapper::map($response);
			}
		}
	}

	public function send(Invoice $invoice) {
		if($this->getLogin()->process()) {
			$service = $this->getService();

			$invoicesDocument = new DOM\InvoicesDocument();
			$invoicesDocument->addInvoice($invoice);

			$response = $service->send($invoicesDocument);
			$this->setResponse($response);

			if ($response->isSuccessful()) {
				return true;
			} else {
				return false;
			}

		}
	}
}