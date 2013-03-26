<?php

namespace Pronamic\Twinfield\DOM;

use Pronamic\Twinfield\Secure\Document as SecureDocument;

abstract class Element {
	private $document;

	public function setDocument( SecureDocument $document ) {
		$this->document = $document;
		return $this;
	}

	public function getDocument() {
		return $this->document;
	}

	abstract public function add();
}