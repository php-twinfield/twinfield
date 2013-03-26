<?php

namespace Pronamic\Twinfield\Response;

use \Pronamic\Twinfield\DOM\Document;

class Response {

	private $responseDocument;
	private $sentDocument;

	public function __construct( \DOMDocument $responseDocument, Document $sentDocument ) {
		$this->responseDocument = $responseDocument;
		$this->sentDocument = $sentDocument;
	}

	public function getResponseDocument() {
		return $this->responseDocument;
	}

	public function isSuccessful() {
		// Gets the elements to check from the child class
		$elementsToCheck = $this->sentDocument->getElementsToCheck();

		// @todo require an exception
		if ( empty( $elementsToCheck ) )
			return false;

		// Loop through each set checkElement
		foreach ( $elementsToCheck as $element => $attributeName ) {
			// Make a temp DOM element
			$tempElement = $this->responseDocument->getElementsByTagName( $element );

			// Multiple elements found
			if ( is_array( $tempElement ) && 1 > count( $tempElement ) ) {
				// Check each element
				foreach ( $tempElement as $tElement ) {
					if ( 1 != $tElement->getAttribute( $attributeName ) )
						return false;
				}

				return true;

			} else {
				// Singular
				$responseValue = $tempElement->item(0)->getAttribute( $attributeName );

				if ( 1 == $responseValue ) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

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