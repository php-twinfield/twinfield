<?php

namespace Pronamic\Twinfield;

/**
 * Title: Transaction header
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class TransactionHeader {
	/**
	 * The code of this office
	 * 
	 * @var string
	 */
	private $office;

	/**
	 * The sales invoice type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * The sales invoice number
	 * 
	 * @var string
	 */
	private $invoiceNumber;

	/**
	 * The invoice date
	 * 
	 * @var DateTime
	 */
	private $invoiceDate;

	/**
	 * The due date
	 * 
	 * @var DateTime
	 */
	private $dueDate;

	/**
	 * The bank
	 * 
	 * @var string
	 */
	private $bank;

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Constructs and initializes an sales invoice header object
	 */
	public function __construct() {
		
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get the office of the sales invoice
	 * 
	 * @return string
	 */
	public function getOffice() {
		return $this->office;
	}

	/**
	 * Set the office of the sales invoice
	 * 
	 * @return string
	 */
	public function setOffice($code) {
		$this->office = $code;
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get the type
	 * 
	 * @return string
	 */
	public function getInvoiceType() {
		return $this->type;
	}

	/**
	 * Set the type
	 * 
	 * @return string
	 */
	public function setInvoiceType($type) {
		$this->type = $type;
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get the number
	 * 
	 * @return string
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * Set the number
	 * 
	 * @return string
	 */
	public function setNumber($number) {
		$this->number = $number;
	}
}
