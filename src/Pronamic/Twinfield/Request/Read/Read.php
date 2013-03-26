<?php

namespace Pronamic\Twinfield\Request\Read;

use Pronamic\Twinfield\Secure\Document as SecureDocument;

abstract class Read extends SecureDocument {

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


