<?php

namespace Pronamic\Twinfield\Request\Read;

class Invoice extends Read {

	public function __construct() {
		parent::__construct();

		$this->add( 'type', 'salesinvoice' );
	}

	public function setOffice( $office ) {
		$this->add( 'office', $office );
		return $this;
	}

	public function setCode( $code ) {
		$this->add( 'code', $code );
		return $this;
	}

	public function setNumber( $number ) {
		$this->add( 'invoicenumber', $number );
		return $this;
	}
}