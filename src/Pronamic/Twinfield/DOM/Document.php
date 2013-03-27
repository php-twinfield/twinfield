<?php

namespace Pronamic\Twinfield\DOM;

/**
 * Abstract class that all Document generators for
 * each component should extend.
 *
 * It contains 2 methods that are required when you
 * pass the Element ( which extends its document, which
 * in turn extends the SecureDocument )
 *
 * It is recommended to call setElementsToCheck from
 * inside each components constructor
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
abstract class Document extends \DOMDocument {

	private $elementsToCheck = array();

	/**
	 * Sets elements for the childs DOCUMENT implementation
	 * to check for the result attribute.
	 *
	 * Used in Pronamic\Twinfield\Response
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string OR array $elements_array Element tags to check for result
	 */
	protected function setElementsToCheck( $elements_array ) {
		if ( ! is_array( $elements_array ) ) {
			$this->elementsToCheck[] = $elements_array;
		} else {
			$this->elementsToCheck = $elements_array;
		}
	}

	/**
	 * Returns all set elements to check.
	 *
	 * This method is used by the Secure\Service class
	 * when a Document is requested to be sent.
	 *
	 * Used in Pronamic\Twinfield\Response
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return array
	 */
	public function getElementsToCheck() {
		return $this->elementsToCheck;
	}

}