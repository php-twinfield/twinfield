<?php

namespace Pronamic\Twinfield\Request\Catalog;

class Office extends Catalog {
	public function __construct() {
		$this->add( 'type', 'office' );
	}
}