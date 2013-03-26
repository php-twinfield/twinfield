<?php

namespace Pronamic\Twinfield\Transaction;

use Pronamic\Twinfield\DOM\ParentDOMLoader;

/**
 * Invoice DOM Builder
 */
class Transaction extends ParentDOMLoader {

	private $destiny;
	private $raise_warning;

	/**
	 * Header Information
	 */
	private $code;
	private $currency;
	private $date;
	private $period;
	private $invoiceNumber;
	private $dueDate;


	public function __construct( $destiny = null, $raise_warning = false ) {

		parent::__construct( 'transaction' );

		$this->destiny = $destiny;
		$this->raise_warning = $raise_warning;
	}

	public function setCode( $code ) {
		$this->code = $code;
		return $this;
	}

	public function getCode() {
		return $this->code;
	}

	public function setCurrency( $currency ) {
		$this->currency = $currency;
		return $this;
	}

	public function getCurrency() {
		return $this->currency;
	}

	public function setDate( \DateTime $date ) {
		$this->date = $date->format('YMD');
		return $this;
	}

	public function getDate() {
		return DateTime::createFromFormat('YMD', $this->date);
	}

	public function setPeriod( $period ) {
		$this->period = $period;
		return $this;
	}

	public function getPeriod() {
		return $this->period;
	}

	public function setInvoiceNumber( $invoiceNumber ) {
		$this->invoiceNumber = $invoiceNumber;
		return $this;
	}

	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	public function setDueDate( \DateTime $date ) {
		$this->dueDate = $date->format('YMD');
		return $this;
	}

	public function getDueDate() {
		return DateTime::createFromFormat('YMD', $this->dueDate);
	}
}