<?php

namespace Pronamic\Twinfield\Invoice;

/**
 * Invoice Class
 *
 * Is an object for mapping data from a response ( or making
 * a request ).
 *
 * It is normally passed into the Element class to convert it
 * into the XML format.
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Invoice
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Invoice {

	/**
	 * A customer for this invoice
	 *
	 * @access private
	 * @var \Pronamic\Customer\Customer
	 */
	private $customer;

	/**
	 * The type of invoice this
	 * object is
	 *
	 * @access private
	 * @var string
	 */
	private $invoiceType;

	private $office;

	private $invoiceNumber;
	private $status;
	private $currency;
	private $period;
	private $invoiceDate;
	private $dueDate;
	private $performanceDate;
	private $paymentMethod;
	private $bank;
	private $invoiceAddressNumber;
	private $deliverAddressNumber;
	private $headerText;
	private $footerText;

	/**
	 * Holds the invoice lines for this
	 * invoice.
	 *
	 * @see \Pronamic\Twinfield\Invoice\InvoiceLine()
	 *
	 * @access private
	 * @var array of InvoiceLine
	 */
	private $lines = array();

	/**
	 * Adds a line to this invoice.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param \Pronamic\Twinfield\Invoice\InvoiceLine $order
	 * @return \Pronamic\Twinfield\Invoice\Invoice
	 */
	public function addLine(InvoiceLine $line) {
		$this->lines[$line->getID()] = $line;
		return $this;
	}

	/**
	 * Removes a line from this invoice
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $uid The unique key for this order
	 * @return boolean If the line was removed
	 */
	public function removeLine( $uid ) {
		if ( array_key_exists( $uid, $this->lines ) ) {
			unset( $this->lines[$uid] );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns all lines currently assigned
	 * to this invoice
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return array of InvoiceLine
	 */
	public function getLines() {
		return $this->lines;
	}

	/**
	 * Gets the current assigned customer
	 * for this invoice
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \Pronamic\Twinfield\Customer\Customer
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * Sets the customer for this invoice
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param \Pronamic\Twinfield\Customer\Customer $customer
	 * @return \Pronamic\Twinfield\Invoice\Invoice
	 */
	public function setCustomer( \Pronamic\Twinfield\Customer\Customer $customer ) {
		$this->customer = $customer;
		return $this;
	}

	/**
	 * Returns the set invoice type
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return string
	 */
	public function getInvoiceType() {
		return $this->invoiceType;
	}

	/**
	 * Sets the type for this invoice
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $invoiceType
	 * @return \Pronamic\Twinfield\Invoice\Invoice
	 */
	public function setInvoiceType( $invoiceType ) {
		$this->invoiceType = $invoiceType;
		return $this;
	}

	public function getOffice() {
		return $this->office;
	}

	public function setOffice( $office ) {
		$this->office = $office;
		return $this;
	}

	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	public function setInvoiceNumber( $invoiceNumber ) {
		$this->invoiceNumber = $invoiceNumber;
		return $this;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus( $status ) {
		$this->status = $status;
		return $this;
	}

	public function getCurrency() {
		return $this->currency;
	}

	public function setCurrency( $currency ) {
		$this->currency = $currency;
		return $this;
	}

	public function getPeriod() {
		return $this->period;
	}

	public function setPeriod( $period ) {
		$this->period = $period;
		return $this;
	}

	public function getInvoiceDate() {
		return $this->invoiceDate;
	}

	public function setInvoiceDate( $invoiceDate ) {
		$this->invoiceDate = $invoiceDate;
		return $this;
	}

	public function getDueDate() {
		return $this->dueDate;
	}

	public function setDueDate( $dueDate ) {
		$this->dueDate = $dueDate;
		return $this;
	}

	public function getPerformanceDate() {
		return $this->performanceDate;
	}

	public function setPerformanceDate( $performanceDate ) {
		$this->performanceDate = $performanceDate;
		return $this;
	}

	public function getPaymentMethod() {
		return $this->paymentMethod;
	}

	public function setPaymentMethod( $paymentMethod ) {
		$this->paymentMethod = $paymentMethod;
		return $this;
	}

	public function getBank() {
		return $this->bank;
	}

	public function setBank( $bank ) {
		$this->bank = $bank;
		return $this;
	}

	public function getInvoiceAddressNumber() {
		return $this->invoiceAddressNumber;
	}

	public function setInvoiceAddressNumber( $invoiceAddressNumber ) {
		$this->invoiceAddressNumber = $invoiceAddressNumber;
		return $this;
	}

	public function getDeliverAddressNumber() {
		return $this->deliverAddressNumber;
	}

	public function setDeliverAddressNumber( $delivererAddressNumber ) {
		$this->deliverAddressNumber = $delivererAddressNumber;
		return $this;
	}

	public function getHeaderText() {
		return $this->headerText;
	}

	public function setHeaderText( $headerText ) {
		$this->headerText = $headerText;
		return $this;
	}

	public function getFooterText() {
		return $this->footerText;
	}

	public function setFooterText( $footerText ) {
		$this->footerText = $footerText;
		return $this;
	}
}