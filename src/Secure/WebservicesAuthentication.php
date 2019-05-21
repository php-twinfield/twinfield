<?php

namespace PhpTwinfield\Secure;

use PhpTwinfield\Exception;
use PhpTwinfield\Services\BaseService;

class WebservicesAuthentication extends AuthenticatedConnection
{
    /**
     * @var string
     */
    private $userName;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $organization;

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

    public function __construct(string $userName, string $password, string $organization)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->organization = $organization;
    }

    /**
     * Login using userName / password / organization combo.
     *
     * @throws Exception
     */
    protected function login(): void
    {
        if ($this->sessionID) {
            return;
        }

        $loginService = new class extends BaseService {

            private const LOGIN_OK = "Ok";

            /**
             * @param string $userName
             * @param string $password
             * @param string $organization
             * @return string[]
             * @throws Exception
             */
            public function getSessionIdAndCluster(string $userName, string $password, string $organization): array
            {
                $response = $this->Logon([
                    "user"         => $userName,
                    "password"     => $password,
                    "organisation" => $organization,
                ]);

                $result = $response->LogonResult;

                // Check response is successful
                if ($result !== self::LOGIN_OK) {
                    throw new Exception("Failed logging in using the credentials, result was \"{$result}\".");
                }

                // Response from the logon request
                $loginResponse = $this->__getLastResponse();

                // Make a new DOM and load the response XML
                $envelope = new \DOMDocument();
                $envelope->loadXML($loginResponse);

                // Gets SessionID
                $sessionIdElements = $envelope->getElementsByTagName('SessionID');
                $sessionId = $sessionIdElements->item(0)->textContent;

                // Gets Cluster URL
                $clusterElements = $envelope->getElementsByTagName('cluster');
                $cluster = $clusterElements->item(0)->textContent;

                return [$sessionId, $cluster];
            }

            final protected function WSDL(): string
            {
                return "https://login.twinfield.com/webservices/session.asmx?wsdl";
            }
        };

        [$this->sessionID, $this->cluster] = $loginService->getSessionIdAndCluster($this->userName, $this->password, $this->organization);
    }

    protected function getSoapHeaders()
    {
        return new \SoapHeader(
            'http://www.twinfield.com/',
            'Header',
            ['SessionID' => $this->sessionID]
        );
    }

    protected function getCluster(): string
    {
        return $this->cluster;
    }
}