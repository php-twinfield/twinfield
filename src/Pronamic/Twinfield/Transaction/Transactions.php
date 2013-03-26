<?php

namespace Pronamic\Twinfield\DOM;

use Pronamic\Twinfield\DOM\ParentDOMLoader;

/**
 * Invoice DOM Builder
 */
class Transactions extends ParentDOMLoader {

	public function __construct() {
		parent::__construct( 'transactions' );
	}

	public function addTransaction( Pronamic\Twinfield\DOM\Transaction $transaction ) {
		$this->XML->appendChild( $transaction );
	}
}