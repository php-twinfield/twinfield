<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Exception;
use PhpTwinfield\Secure\Config;

class LoginService extends BaseService
{
    /**
     * Login based on the config.
     *
     * @param Config $config
     * @throws Exception
     * @return string[]
     */
    public function getSessionIdAndCluster(Config $config): array
    {
        // Process logon
        if (!empty($config->getClientToken())) {
            $response = $this->OAuthLogon($config->getCredentials());
            $result = $response->OAuthLogonResult;
        } else {
            $response = $this->Logon($config->getCredentials());
            $result = $response->LogonResult;
        }

        // Check response is successful
        if ($result !== 'Ok') {
            throw new Exception("Failed logging in using the credentials.");
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

    protected function WSDL(): string
    {
        return "https://login.twinfield.com/webservices/session.asmx?wsdl";
    }
}