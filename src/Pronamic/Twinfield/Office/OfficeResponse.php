<?php

namespace Pronamic\Twinfield\Office;

use \Pronamic\Twinfield\Response\Response;

class OfficeResponse extends ResponseMap {

	public function __construct( Response $response ) {
		parent::__construct( $response );
	}

	public function eachMap( \DOMElement $element, \DOMNode $node ) {

		$office = new Office();

		$office->setCode( $node->nodeValue );
		$office->setName( $element->getAttr( 'name' ) );
		$office->setShortName( $element->getAttribute( 'shortname' ) );

		return $office;

	}

}