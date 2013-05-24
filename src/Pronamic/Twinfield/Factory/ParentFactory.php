<?php

namespace Pronamic\Twinfield\Factory;

use \Pronamic\Twinfield\Secure\Config;
use \Pronamic\Twinfield\Secure\Login;
use \Pronamic\Twinfield\Secure\Service;

abstract class ParentFactory {
	private $config;
	private $login;

	public function __construct(Config $config) {
		$this->setConfig($config);
		$this->makeLogin();
	}

	public function setConfig(Config $config) {
		$this->config = $config;
		return $this;
	}

	public function getConfig() {
		return $this->config;
	}

	public function makeLogin() {
		try {
			$this->login = new Login($this->getConfig());
		} catch ( Exception $exc ) {
			echo $exc->getTraceAsString();
		}
	}

	public function getLogin() {
		return $this->login;
	}

	public function getService() {
		return new Service($this->getLogin());
	}

	public function setResponse(\Pronamic\Twinfield\Response\Response $response) {
		$this->response = $response;
		return $this;
	}

	public function getResponse() {
		return $this->response;
	}
}