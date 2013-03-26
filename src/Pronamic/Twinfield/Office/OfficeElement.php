<?php

namespace Pronamic\Twinfield\Office;

class OfficeElement extends OfficesDocument {
	public function getAll() {
		$listElement = $this->getListElement();

		$typeElement = $this->createElement( 'type', 'offices' );

		$listElement->appendChild( $typeElement );
	}
}
