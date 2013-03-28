<?php

namespace Pronamic\Twinfield\Request\Read;

abstract class Read extends \DOMDocument {

	private $readElement;

	public function __construct() {
		parent::__construct();

		$this->readElement = $this->createElement( 'read' );
		$this->appendChild( $this->readElement );
	}

	public function add( $element, $value ) {
		$_element = $this->createElement( $element, $value );
		$this->readElement->appendChild( $_element );
	}
}


