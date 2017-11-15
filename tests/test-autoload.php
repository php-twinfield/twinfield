<?php

class Pronamic_Twinfield_Autoload_Test extends PHPUnit_Framework_TestCase {
    function test_autoload() {
        $class_exists = class_exists( '\PhpTwinfield\Secure\Login' );

        // Assert
        $this->assertTrue( $class_exists );
    }
}
