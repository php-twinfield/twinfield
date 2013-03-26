<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\SalesInvoiceHeader;

/**
 * Title: Sales invoice header XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SalesInvoiceHeaderParser {
	public static function parse(\SimpleXMLElement $xml) {
		$header = new SalesInvoiceHeader();

		$header->setOffice(filter_var($xml->office, FILTER_SANITIZE_STRING));
		$header->setInvoiceType(filter_var($xml->invoicetype, FILTER_SANITIZE_STRING));
		$header->setInvoiceNumber(filter_var($xml->invoicenumber, FILTER_SANITIZE_STRING));

		$invoice_date = filter_var( $xml->invoicedate, FILTER_SANITIZE_STRING );
		$invoice_date = strtotime( $invoice_date );

		$header->setInvoiceDate( new \DateTime( '@' . $invoice_date ) );

		$due_date = filter_var( $xml->duedate, FILTER_SANITIZE_STRING );
		$due_date = strtotime( $due_date );

		$header->setDueDate( new \DateTime( '@' . $due_date ) );

		$header->setStatus( filter_var( $xml->status, FILTER_SANITIZE_STRING ) );

		$header->setCustomer( filter_var( $xml->customer, FILTER_SANITIZE_STRING ) );

		return $header;
	}
}
