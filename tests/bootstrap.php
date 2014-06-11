<?php

function twinfield_autoload( $className ) {
	$path = dirname( __FILE__ ) . '/../src/';

	$className = ltrim($className, '\\');
	$fileName = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	if ( file_exists( $path . $fileName ) ) {
		require $path . $fileName;
	}
}

spl_autoload_register( 'twinfield_autoload' );
