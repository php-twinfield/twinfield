<?php
namespace PhpTwinfield\Secure;

use PhpTwinfield\Exception;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Services\BaseService;
use PhpTwinfield\Services\LoginService;
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
     * @var LoginService
     */
    private $loginService;

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
    private $cluster = 'https://c3.twinfield.com';

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->cluster = !is_null($config->cluster) ? $config->cluster : $this->cluster;
        $this->loginService = new LoginService(null, $config->getSoapClientOptions());
    }

    /**
     * @throws Exception
     */
    protected function login()
    {
        if ($this->sessionID) {
            return;
        }

        [$this->sessionID, $this->cluster] = $this->loginService->getSessionIdAndCluster($this->config);
    }

    /**
     * @var BaseService[]
     */
    private $authenticatedClients = [];

    /**
     * Get an authenticated client for a specific service/
     *
     * @param Services $service
     * @throws Exception
     * @return BaseService
     */
    public function getAuthenticatedClient(Services $service): BaseService
    {
        $this->login();

        $key = $service->getKey();

        if (!array_key_exists($key, $this->authenticatedClients)) {

            $classname = $service->getValue();

            $this->authenticatedClients[$key] = new $classname(
                "{$this->cluster}{$service->getValue()}",
                $this->config->getSoapClientOptions() + ["cluster" => $this->cluster]
            );

            $this->authenticatedClients[$key]->__setSoapHeaders(new \SoapHeader(
                'http://www.twinfield.com/',
                'Header',
                array('SessionID' => $this->sessionID)
            ));
        }

        return $this->authenticatedClients[$key];
    }
}
