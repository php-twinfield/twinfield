<?php

namespace Pronamic\Twinfield;

use Pronamic\Twinfield\XML\OfficeParser;

/**
 * Title: Twinfield client
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class TwinfieldClient {
	/**
	 * The Twinfield SOAP WSDL URI
	 *
	 * @var string
	 */
	const SOAP_WSDL_LOGIN_URI = 'https://login.twinfield.com/webservices/session.asmx?wsdl';

	/**
	 * The Twinfield cluster SOAP WSDL URI
	 *
	 * @var string
	 */
	const SOAP_WSDL_CLUSTER_URI = '%s/webservices/processxml.asmx?wsdl';

	///////////////////////////////////////////////////////////////////////////

	/**
	 * SOAP XML namespace
	 *
	 * @var string
	 */
	const XML_NAMESPACE_SOAP_ENVELOPE = 'http://schemas.xmlsoap.org/soap/envelope/';

	///////////////////////////////////////////////////////////////////////////

	/**
	 * The SOAP client
	 *
	 * @var \SoapClient
	 */
	private $soapLoginClient;

	/**
	 * The cluster SOAP client
	 *
	 * @var \SoapClient
	 */
	private $soapClusterClient;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * The cluster
	 *
	 * @var string
	 */
	private $cluster;

	/**
	 * The session id
	 *
	 * @var string
	 */
	private $sessionId;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Twinfield client
	 */
	public function __construct() {
		$this->soapLoginClient = new \SoapClient(self::SOAP_WSDL_LOGIN_URI, array(
			'trace' => 1 ,
			'classmap' => array(
				'LogonResponse' => __NAMESPACE__ . '\LogonResponse'
			)
		));

		$this->error = new \stdClass();
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * SOAP call
	 *
	 * @param string $functionName
	 * @param mixed $arguments
	 */
	private function soapCall($functionName, $arguments = array()) {
		$result = null;

		try {
			$result = $this->soapClusterClient->__soapCall($functionName, $arguments, null, $this->soapInputHeader);
		} catch(\SoapFault $e) {
			$response = $this->soapLoginClient->__getLastResponse();

			echo '<pre>';
			var_dump($e);
			echo '</pre>';
			echo '<hr />';
			echo '<h2>SoapFault response</h2>';
			echo '<pre>';
			var_dump($e->detail);
			echo '</pre>';
		}

		return $result;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Login
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $organisation
	 * @todo should this function create a new SOAP clust clienst?
	 * @throw SoapFault
	 */
	public function logon($username, $password, $organisation) {
		$return = false;

		$parameters = array(
			'user' => $username ,
			'password' => $password ,
			'organisation' =>  $organisation
		);

		$logonResponse = $this->soapLoginClient->logon($parameters);

		if($logonResponse->LogonResult == LogonResult::OK) {
			// Read the SOAP client last response
			$response = $this->soapLoginClient->__getLastResponse();
/*
//echo htmlentities($response);
echo '<pre>';
var_dump($this->soapLoginClient->__getTypes());
echo '</pre>';
echo '<pre>';
var_dump($logonResponse);
echo '</pre>';
*/
			// Create an SimpleXML object so we can easy access XML elements
			$envelope = simplexml_load_string($response, null, null, self::XML_NAMESPACE_SOAP_ENVELOPE);

			// Get the Twinfield header element from SOAP evenlope header
			$header = $envelope->Header->children(Twinfield::XML_NAMESPACE);

			// Save the clust and session ID data
			$this->cluster = $logonResponse->cluster;
			$this->sessionId = $header->Header->SessionID;

			// Create an SOAP input header for further SOAP request
			$this->soapInputHeader = new \SoapHeader(Twinfield::XML_NAMESPACE, 'Header', array('SessionID' => $this->sessionId));

			// Create a new SOAP client that connects to the cluster
			$wsdlClusterUrl = sprintf(self::SOAP_WSDL_CLUSTER_URI, $this->cluster);

			$this->soapClusterClient = new \SoapClient($wsdlClusterUrl);

			$return = true;
		}

		return $return;
	}


	///////////////////////////////////////////////////////////////////////////

	/**
	 * Process XML string
	 *
	 * @param string $xml
	 */
	public function processXmlString($xml) {
		$result = $this->soapCall('ProcessXmlString', array(array('xmlRequest' => $xml)));

		return $result;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the offices
	 *
	 * @return array
	 */
	public function getOffices() {
		$xml = new \SimpleXMLElement('<list />');
		$xml->addChild('type', 'offices');

		$result = $this->soapCall('ProcessXmlString', array(array('xmlRequest' => $xml->asXML())));

		$xmlOffices = simplexml_load_string($result->ProcessXmlStringResult);

		$offices = array();

		foreach($xmlOffices->office as $element) {
			$offices[] = XML\OfficeParser::parse($element);
		}

		return $offices;
	}

	///////////////////////////////////////////////////////////////////////////

	public function readSalesInvoice($office, $type, $code) {
		$xml = new \SimpleXMLElement('<read />');
		$xml->addChild('type', Read::TYPE_SALES_INVOICE);
		$xml->addChild('office', $office);
		$xml->addChild('code', $type);
		$xml->addChild('invoicenumber', $code);

		$result = $this->soapCall('ProcessXmlString', array(array('xmlRequest' => $xml->asXML())));

		$xml = simplexml_load_string($result->ProcessXmlStringResult);

		$result = filter_var($xml['result'], FILTER_VALIDATE_BOOLEAN);

		if($result) {
			return XML\SalesInvoiceParser::parse($xml);
		} else {
			return null;
		}
	}

	///////////////////////////////////////////////////////////////////////////

	public function readTransaction($office, $code, $number) {
		$xml = new \SimpleXMLElement('<read />');
		$xml->addChild('type', Read::TYPE_TRANSACTION);
		$xml->addChild('office', $office);
		$xml->addChild('code', $code);
		$xml->addChild('number', $number);

		$result = $this->soapCall('ProcessXmlString', array(array('xmlRequest' => $xml->asXML())));

		$xml = simplexml_load_string($result->ProcessXmlStringResult);

		if($result) {
			return XML\TransactionParser::parse($xml);
		} else {
			return null;
		}

		return $result;
	}

	///////////////////////////////////////////////////////////////////////////

	public function readDimension($office, $dimensionType, $code) {
		$xml = new \SimpleXMLElement('<read />');
		$xml->addChild('type', Read::TYPE_DIMENSIONS);
		$xml->addChild('office', $office);
		$xml->addChild('dimtype', $dimensionType);
		$xml->addChild('code', $code);

		$result = $this->soapCall('ProcessXmlString', array(array('xmlRequest' => $xml->asXML())));

		return $result;
	}

	public function read_debtor( $office, $code ) {
		$result = self::readDimension($office, Read::DIMENSION_TYPE_DEBTOR, $code);

		$xml = simplexml_load_string($result->ProcessXmlStringResult);
		
		$debtor = new Debtor();

		$office = XML\OfficeParser::parse($xml->office);
		$debtor->setOffice($office);

		$debtor->setName((string) $xml->name);
		$debtor->setWebsite((string) $xml->website);

		$addresses = XML\AddressesParser::parse($xml->addresses);
		$debtor->setAddresses($addresses);

		/*
		 echo '<pre>';
		echo htmlentities($xml->asXML());
		echo '</pre>';
		*/

		return $debtor;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get finder
	 *
	 * @return Finder
	 */
	public function getFinder() {
		$finder = new Finder($this->cluster, $this->soapInputHeader);

		return $finder;
	}
}
