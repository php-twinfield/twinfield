<?php

namespace Pronamic\Twinfield\Request\Catalog;

abstract class Catalog extends \DOMDocument {

	private $listElement;

	public function __construct() {
		parent::__construct();

		$this->listElement = $this->createElement( 'list' );
		$this->appendChild( $this->listElement );
	}

	protected function add( $element, $value ) {
		$_element = $this->createElement( $element, $value );
		$this->listElement->appendChild( $_element );
	}
}