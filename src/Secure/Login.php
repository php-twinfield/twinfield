<?php
namespace PhpTwinfield\Secure;

use Webmozart\Assert\Assert;

/**
 * Login Class.
 *
 * Used to return an instance of a Soapclient for further interaction
 * with Twinfield services.
 *
 * The username, password and organisation are retrieved from the options
 * on construct.
 *
 * @uses \PhpTwinfield\Secure\Config    Holds all the config settings for this account
 * @uses \SoapClient                          For both login and future interactions
 * @uses \SoapHeader                          Generation of the secure header
 * @uses \DOMDocument                         Handles the response from login
 *
 * @since 0.0.1
 *
 * @package PhpTwinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Login
{
    private const LOGIN_WSDL = 'https://login.twinfield.com/webservices/session.asmx?wsdl';
    private const CLUSTER_WSDL_TEMPLATE = '%s/webservices/processxml.asmx?wsdl';

    /**
     * Holds the passed in Config instance
     * 
     * @access private
     * @var \PhpTwinfield\Secure\Config
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
     * The sessionID for the successful login
     *
     * @access private
     * @var string
     */
    private $sessionID;

    /**
     * @var SoapClient
     */
    private $soapProcessClient;

    /**
     * The server cluster used for future XML
     * requests with the new SoapClient
     *
     * @access private
     * @var string
     */
    private $cluster = 'https://c3.twinfield.com';

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
        $this->soapLoginClient = new SoapClient(self::LOGIN_WSDL, $config->getSoapClientOptions());
    }

    /**
     * Will process the login if no session exists yet.
     *
     * If successful, will set the session and cluster information
     * to the class
     *
     * @since 0.0.1
     *
     * @access public
     * @return boolean If successful or not
     */
    protected function login()
    {
        if ($this->processed) {
            return true;
        }

        // Process logon
        if (!empty($this->config->getClientToken())) {
            $response = $this->soapLoginClient->OAuthLogon($this->config->getCredentials());
            $result = $response->OAuthLogonResult;
        } else {
            $response = $this->soapLoginClient->Logon($this->config->getCredentials());
            $result = $response->LogonResult;
        }

        // Check response is successful
        if ($result == 'Ok') {
            // Response from the logon request
            $loginResponse = $this->soapLoginClient->__getLastResponse();

            // Make a new DOM and load the response XML
            $envelope = new \DOMDocument();
            $envelope->loadXML($loginResponse);

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

        return false; // todo throw
    }

    /**
     * Gets the soap client with the headers attached. Will automatically login if haven't already on this instance.
     *
     * @return SoapClient
     */
    public function getClient(): SoapClient
    {
        $this->login();

        if (null === $this->soapProcessClient) {

            // Makes a new client, and assigns the header to it
            $this->soapProcessClient = new SoapClient(
                sprintf(self::CLUSTER_WSDL_TEMPLATE, $this->cluster),
                $this->config->getSoapClientOptions()
            );

            $this->soapProcessClient->__setSoapHeaders(new \SoapHeader(
                'http://www.twinfield.com/',
                'Header',
                array('SessionID' => $this->sessionID)
            ));
        }

        return $this->soapProcessClient;
    }
}
