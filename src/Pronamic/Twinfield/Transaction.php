<?php

namespace Pronamic\Twinfield;

/**
 * Title: Transaction
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Transaction {
	/**
	 * The header of this transaction
	 * 
	 * @var TransactionHeader
	 */
	private $header;

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Constructs and initializes an sales invoice object
	 */
	public function __construct() {
		
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get the header of this transaction
	 * 
	 * @return TransactionHeader
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * Set the header of this transaction
	 * 
	 * @param TransactionHeader $header
	 */
	public function setHeader($header) {
		$this->header = $header;
	}
}
