<?php

namespace Pronamic\Twinfield\Secure;

class Config {

	private $credentials = array();

	public function setCredentials( $username, $password, $organisation, $office ) {
		$this->credentials['user'] = $username;
		$this->credentials['password'] = $password;
		$this->credentials['organisation'] = $organisation;
		$this->credentials['office'] = $office;
	}

	public function getCredentials() {
		return $this->credentials;
	}

	public function getUsername() {
		return $this->credentials['user'];
	}

	public function getPassword() {
		return $this->credentials['password'];
	}

	public function getOrganisation() {
		return $this->credentials['organisation'];
	}

	public function getOffice() {
		return $this->credentials['office'];
	}
}