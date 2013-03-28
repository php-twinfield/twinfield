<?php

namespace Pronamic\Twinfield\Response;

/**
 * Response class
 *
 * Handles the response from a request.  Has the option
 * to determine if the response was a success or not.
 *
 * @since 0.0.1
 *
 * @uses \DOMDocument
 * @uses \DOMXPath
 *
 * @package Pronamic\Twinfield
 * @subpackage Response
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Response {

	/**
	 * Holds the response, loaded in from the
	 * \Pronamic\Twinfield\Secure\Service class
	 *
	 * @access private
	 * @var \DOMDocument
	 */
	private $responseDocument;

	public function __construct( \DOMDocument $responseDocument ) {
		$this->responseDocument = $responseDocument;
	}

	/**
	 * Returns the raw DOMDocument response.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \DOMDocument
	 */
	public function getResponseDocument() {
		return $this->responseDocument;
	}

	/**
	 * Will determine if the response was a success or not.  It does
	 * this by getting the root element, and looking for the
	 * attribute result.  If it doesn't equal 1, then it failed.
	 *
	 * @since 0.0.1
	 *
	 * @see http://remcotolsma.nl/wp-content/uploads/Twinfield-Webservices-Manual.pdf
	 *
	 * @access public
	 * @return boolean
	 */
	public function isSuccessful() {
		$responseValue = $this->responseDocument->documentElement->getAttribute('result');

		return (bool) $responseValue;
	}

	/**
	 * Will return an array of all error messages found
	 * in the response document.
	 *
	 * It is recommended to run this function after a
	 * isSuccessful check.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return array
	 */
	public function getErrorMessages() {
		$xpath = new \DOMXPath( $this->responseDocument );

		$errors = array();

		$rowNodes = $xpath->query( '//*[@msgtype="error"]');
		foreach ( $rowNodes as $rowNode ) {
			$errors[] = $rowNode->getAttribute('msg');
		}

		return $errors;
	}

}