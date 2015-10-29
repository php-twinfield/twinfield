<?php
namespace Pronamic\Twinfield\Secure;

use Pronamic\Twinfield\SoapClient;

/**
 * Login Class.
 *
 * Used to return an instance of a Soapclient for further interaction
 * with Twinfield services.
 *
 * The username, password and organisation are retrieved from the options
 * on construct.
 *
 * @uses \Pronamic\Twinfield\Secure\Config    Holds all the config settings for this account
 * @uses \SoapClient                          For both login and future interactions
 * @uses \SoapHeader                          Generation of the secure header
 * @uses \DOMDocument                         Handles the response from login
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Login
{
    protected $loginWSDL    = 'https://login.twinfield.com/webservices/session.asmx?wsdl';
    protected $clusterWSDL  = '%s/webservices/processxml.asmx?wsdl';
    protected $xmlNamespace = 'http://schemas.xmlsoap.org/soap/envelope/';
    
    /**
     * Holds the passed in Config instance
     * 
     * @access private
     * @var Pronamic\Twinfield\Secure\Config
     */
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
    public $sessionID;

    /**
     * The server cluster used for future XML
     * requests with the new SoapClient
     *
     * @access private
     * @var string
     */
    public $cluster = 'https://c3.twinfield.com';

    /**
     * If the login has been processed and was
     * successful
     *
     * @access private
     * @var boolean
     */
    private $processed = false;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->cluster = !is_null($config->cluster) ? $config->cluster : $this->cluster;
        $this->soapLoginClient = new SoapClient($this->loginWSDL, array('trace' => 1));
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
    public function process()
    {
        // Process logon
        if ($this->config->getClientToken() != '') {
            $response = $this->soapLoginClient->OAuthLogon($this->config->getCredentials());
            $result = $response->OAuthLogonResult;
        } else {
            $response = $this->soapLoginClient->Logon($this->config->getCredentials());
            $result = $response->LogonResult;
        }
        // Check response is successful
        if ($result == 'Ok') {
            // Response from the logon request
            $this->loginResponse = $this->soapLoginClient->__getLastResponse();

            // Make a new DOM and load the response XML
            $envelope = new \DOMDocument();
            $envelope->loadXML($this->loginResponse);

            // Gets SessionID
            $sessionID       = $envelope->getElementsByTagName('SessionID');
            $this->sessionID = $sessionID->item(0)->textContent;

            // Gets Cluster URL
            $cluster       = $envelope->getElementsByTagName('cluster');
            $this->cluster = $cluster->item(0)->textContent;
            // This login object is processed!
            $this->processed = true;

            return true;
        }

        return false;
    }

    /**
     * Gets a new instance of the soap header.
     *
     * Will automaticly login if haven't already on this instance
     *
     * @since 0.0.1
     *
     * @access public
     * @return \SoapHeader
     */
    public function getHeader()
    {
        if (! $this->processed || is_null($this->cluster)) {
            $this->process();
        }

        return new \SoapHeader(
            'http://www.twinfield.com/',
            'Header',
            array('SessionID' => $this->sessionID)
        );
    }

    /**
     * Gets the soap client with the headers attached
     *
     * Will automatically login if haven't already on this instance
     *
     * @since 0.0.1
     *
	 * @param string|null $wsdl the wsdl to use. If null, the clusterWSDL is used.
     * @access public
     * @return \SoapClient
     */
    public function getClient($wsdl = null)
    {
        if (! $this->processed) {
            $this->process();
        }
		$wsdl = is_null($wsdl) ? $this->clusterWSDL : $wsdl;
        $header = $this->getHeader();
        // Makes a new client, and assigns the header to it
        $client = new SoapClient(sprintf($wsdl, $this->cluster), $this->config->getSoapClientOptions());
        $client->__setSoapHeaders($header);

        return $client;
    }
}
