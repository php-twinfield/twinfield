<?php

namespace Pronamic\Twinfield\Request\Catalog;

class Dimension extends Catalog {

	public function __construct( $office = null, $dimType = null ) {
		parent::__construct();
		$this->add( 'type', 'dimensions' );
		$this->setOffice( $office );
		$this->setDimType( $dimType );
	}

	public function setOffice( $office ) {
		$this->add( 'office', $office );
	}

	public function setDimType( $dimType ) {
		$this->add( 'dimtype', $dimType );
	}


}