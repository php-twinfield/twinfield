<?php

class Pronamic_Twinfield_Autoload_Test extends PHPUnit_Framework_TestCase {
	function test_autoload() {
		$class_exists = class_exists( 'Pronamic\Twinfield\Secure\Login' );

		// Assert
		$this->assertEquals( $class_exists, true );
	}
}
