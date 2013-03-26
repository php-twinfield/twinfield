<?php

namespace Pronamic\Twinfield;

/**
 * Title: Sales invoice
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SalesInvoice {
	/**
	 * Invoice code for an 'factuur' invoice
	 *
	 * @see https://github.com/remcotolsma/wp-twinfield/blob/master/documentation/inv-invoice.xml#L4
	 * @var string
	 */
	const TYPE_FACTUUR = 'factuur';
	
	/**
	 * Invoice code for an 'uren' invoice
	 *
	 * @see https://github.com/remcotolsma/wp-twinfield/blob/master/documentation/inv-invoice.xml#L4
	 * @var string
	 */
	const TYPE_UREN = 'uren';

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * The header of this sales invoice
	 * 
	 * @var string
	 */
	private $header;

	/**
	 * The lines within this sales invoice
	 * 
	 * @var string
	 */
	private $lines;

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Constructs and initializes an sales invoice object
	 */
	public function __construct() {
		$this->lines = array();
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get the header of this sales invoice
	 * 
	 * @return SalesInvoiceHeader
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * Set the header of this sales invoice
	 * 
	 * @param SalesInvoiceHeader $header
	 */
	public function setHeader($header) {
		$this->header = $header;
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Add the specified line to this sales invoice
	 * 
	 * @param SalesInvoiceLine $line
	 */
	public function addLine(SalesInvoiceLine $line) {
		$this->lines[] = $line;
	}

	/**
	 * Get the lines of this sales invoice
	 * 
	 * @return array
	 */
	public function getLines() {
		return $this->lines;
	}
}
