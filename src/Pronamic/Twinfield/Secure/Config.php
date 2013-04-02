<?php

namespace Pronamic\Twinfield\Secure;

/**
 * Config class
 *
 * Used to store the config information used to
 * generate a secure soap client and use the rest
 * of the application.
 *
 * There is no constructor.  You set all the values
 * with the method setCredentials();
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Config {

	/**
	 * Holds all the login details for this
	 * config object
	 *
	 * @access private
	 * @var array
	 */
	private $credentials = array();

	/**
	 * Sets the details for this config
	 * object.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $username
	 * @param string $password
	 * @param string $organisation
	 * @param int $office
	 * @return void
	 */
	public function setCredentials( $username, $password, $organisation, $office ) {
		$this->credentials['user'] = $username;
		$this->credentials['password'] = $password;
		$this->credentials['organisation'] = $organisation;
		$this->credentials['office'] = $office;
	}

	/**
	 * Returns the entire collection of details
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return array
	 */
	public function getCredentials() {
		return $this->credentials;
	}

	/**
	 * Returns the set user
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return string
	 */
	public function getUsername() {
		return $this->credentials['user'];
	}

	/**
	 * Returns the set password
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return string
	 */
	public function getPassword() {
		return $this->credentials['password'];
	}

	/**
	 * Returns the set organisation code
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return string
	 */
	public function getOrganisation() {
		return $this->credentials['organisation'];
	}

	/**
	 * Returns the set office code
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return string
	 */
	public function getOffice() {
		return $this->credentials['office'];
	}
}