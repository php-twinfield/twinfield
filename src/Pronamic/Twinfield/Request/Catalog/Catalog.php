<?php

namespace Pronamic\Twinfield\Request\Catalog;

use Pronamic\Twinfield\Secure\Document as SecureDocument;

abstract class Catalog extends SecureDocument {

	private $listElement;

	public function __construct() {
		parent::__construct();

		$this->listElement = $this->createElement( 'list' );
		$this->appendChild( $this->listElement );
	}

	protected function add( $element, $value ) {
		$_element = $this->createElement( $element, $value );
		$this->appendChild( $_element );
	}
}