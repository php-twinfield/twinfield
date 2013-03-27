<?php

namespace Pronamic\Twinfield\Secure;

/**
 * Login Class.
 *
 * Used to return an instance of a Soapclient for further interaction
 * with Twinfield services.
 *
 * The username, password and organisation are retrieved from the options
 * on construct.
 *
 * @uses \Pronamic\Twinfield\Secure\Config		Holds all the config settings for this account
 * @uses \SoapClient							For both login and future interactions
 * @uses \SoapHeader							Generation of the secure header
 * @uses \DOMDocument							Handles the response from login
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Login {

	protected $loginWSDL = 'https://login.twinfield.com/webservices/session.asmx?wsdl';
	protected $clusterWSDL = '%s/webservices/processxml.asmx?wsdl';
	protected $xmlNamespace = 'http://schemas.xmlsoap.org/soap/envelope/';

	private $config;

	/**
	 * The SoapClient used to login to Twinfield
	 *
	 * @access private
	 * @var SoapClient
	 */
	private $soapLoginClient;

	/**
	 * The response from the login client, when
	 * successful
	 *
	 * @access private
	 * @var string
	 */
	private $loginResponse;

	/**
	 * The sessionID for the successful login
	 *
	 * @access private
	 * @var string
	 */
	private $sessionID;

	/**
	 * The server cluster used for future XML
	 * requests with the new SoapClient
	 *
	 * @access private
	 * @var string
	 */
	private $cluster;

	/**
	 * If the login has been processed and was
	 * successful
	 *
	 * @access private
	 * @var boolean
	 */
	private $processed = false;

	public function __construct( Config $config ) {
		$this->config = $config;

		$this->soapLoginClient = new \SoapClient( $this->loginWSDL, array(
			'trace' => 1
				) );
	}

	/**
	 * Will process the login.
	 *
	 * If successful, will set the session and cluster information
	 * to the class
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return boolean If successful or not
	 */
	public function process() {
		// Process logon
		$response = $this->soapLoginClient->Logon( $this->config->getCredentials() );

		// Check response is successful
		if ( 'Ok' == $response->LogonResult ) {
			// Response from the logon request
			$this->loginResponse = $this->soapLoginClient->__getLastResponse();

			// Make a new DOM and load the response XML
			$envelope = new \DOMDocument();
			$envelope->loadXML( $this->loginResponse );

			// Gets SessionID
			$sessionID = $envelope->getElementsByTagName( 'SessionID' );
			$this->sessionID = $sessionID->item( 0 )->textContent;

			// Gets Cluster URL
			$cluster = $envelope->getElementsByTagName( 'cluster' );
			$this->cluster = $cluster->item( 0 )->textContent;

			$this->setCookies();

			// This login object is processed!
			$this->processed = true;

			return true;
		}

		return false;
	}

	/**
	 * Sets the cookie for the twinfield api.
	 *
	 * If no parameters are passed will use the values from the class
	 * properties.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $sessionID OPTIONAL The Session Information
	 * @param string $cluster OPTIONAL The Cluster URL
	 * @return void
	 */
	public function setCookies( $sessionID = null, $cluster = null ) {
		if ( ! $sessionID )
			$sessionID = $this->sessionID;

		if ( ! $cluster )
			$cluster = $this->cluster;

		// Sets cookies to time + 1 hour ( 3600 seconds )
		setcookie( 'twinfield_session_id', $sessionID, time() + 3600, '/' );
		setcookie( 'twinfield_cluster', $cluster, time() + 3600, '/' );
	}

	/**
	 * Removes the existing cookies.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return void
	 */
	public function removeCookies() {
		setcookie( 'twinfield_session_id', '', 1);
		setcookie( 'twinfield_cluster', '', 1);
	}

	/**
	 * Gets a new instance of the soap header.
	 *
	 * Will automaticly login if haven't already on this object
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \SoapHeader
	 */
	public function getHeader() {
		if ( ! $this->processed )
			$this->process();

		return new \SoapHeader( 'http://www.twinfield.com/', 'Header', array( 'SessionID' => $this->sessionID ) );
	}

	/**
	 * Gets the soap client with the headers attached
	 *
	 * Will automaticly login if haven't already on this object
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \SoapClient
	 */
	public function getClient() {
		if ( ! $this->processed )
			$this->process();

		// Makes a new client, and assigns the header to it
		$client = new \SoapClient( sprintf( $this->clusterWSDL, $this->cluster ) );
		$client->__setSoapHeaders( $this->getHeader() );

		return $client;
	}

}