<?php

namespace Pronamic\Twinfield\Response;

class ResponseDocument extends \DOMDocument {
	
	public function __construct($xmlString) {
		parent::__construct();
		
		$this->loadXML($xmlString);
	}
	
	public function first( $tag_name ) {
		$element = $this->getElementsByTagName($tag_name)->item(0);
		
		if(isset($element) && isset($element->textContent)) {
			return $element->textContent;
		} else {
			return '';
		}
	}
	
}