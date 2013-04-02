<?php

namespace Pronamic\Twinfield\Request\Read;

class Customer extends Read {
	
	public function __construct() {
		parent::__construct();
		
		$this->add( 'type', 'dimensions' );
		$this->add( 'dimtype', 'DEB' );
	}
	
	public function setOffice($office) {
		$this->add('office', $office);
		return $this;
	}
	
	public function setCode($code) {
		$this->add('code', $code);
		return $this;
	}
}