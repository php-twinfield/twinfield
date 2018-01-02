<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Exception;
use PhpTwinfield\Secure\Config;

class LoginService extends BaseService
{
    private const LOGIN_OK = "Ok";

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
            $response = $this->Logon($v = [
                "user"         => $config->getUsername(),
                "password"     => $config->getPassword(),
                "organisation" => $config->getOrganisation(),
            ]);
            $result = $response->LogonResult;
        }

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
}