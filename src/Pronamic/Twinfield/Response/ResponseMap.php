<?php

namespace Pronamic\Twinfield\Response;

abstract class ResponseMap {

	private $response;

	private $loopElements;

	public function __construct( Response $response, $loopElements ) {
		$this->response = $response;
	}

	public function map() {

	}
}