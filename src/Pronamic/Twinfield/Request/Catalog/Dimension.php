<?php

namespace Pronamic\Twinfield\Request\Catalog;

class Dimension {
	
	public function __construct( $office = null, $dimType = null ) {
		$this->add( 'type', 'dimension' );
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