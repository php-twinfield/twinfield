<?php

namespace Pronamic\Twinfield;

/**
 * Title: Twinfield client
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Cluster {

	///////////////////////////////////////////////////////////////////////////	
  
	/**
	 * The SOAP client
	 * 
	 * @var \SoapClient
	 */
	private $soapClient;

	/**
	 * The default SOAP header
	 * 
	 * @var \SoapHeader
	 */
	private $soapHeader;

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Constructs and initializes an Twinfield client
	 */
	public function __construct($cluster, $sessionId) {
		$wsdlUri = sprintf(self::SOAP_WSDL_URI, $cluster);

		$this->soapClient = new \SoapClient($wsdlUri);
		$this->soapHeader = new \SoapHeader(self::SOAP_HEADER_NAMESPACE, 'Header', array('SessionID' => $sessionId));

		$this->error = new \stdClass();
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Login
	 * 
	 * @param string $username
	 * @param string $password
	 * @param string $organisation
	 */
	public function logon($username, $password, $organisation) {
		$result = false;

		try {
			$parameters = array(
				'user' => $username , 
				'password' => $password , 
				'organisation' =>  $organisation
			);

			$result = $this->soapClient->logon($parameters);
var_dump($result);
			if($result->LogonResult == 'Ok') {
				$wsdlUrl = $result->cluster;
			}
		} catch(SoapFault $e) {
			$error->soapFault = $e;
		}

		return $result;
	}
}
