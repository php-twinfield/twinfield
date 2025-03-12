<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Exception;
use PhpTwinfield\Office;

class SessionService extends BaseService {

    private const CHANGE_OK = "Ok";

    /**
     * SessionService constructor.
     *
     * @param string|null $wsdl
     * @param array $options
     */
    public function __construct(?string $wsdl = null, array $options = [])
    {
        // If no cluster is set, it means we're dealing with a login call
        // Hence we set the cluster to use the authentication url
        if(! isset($options['cluster'])) {
            $options['cluster'] = 'https://login.twinfield.com';
        }

        parent::__construct($wsdl, $options);
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $organization
     * @return string[]
     * @throws Exception
     */
    public function getSessionIdAndCluster(string $username, string $password, string $organization): array
    {
        $response = $this->Logon([
            "user"         => $username,
            "password"     => $password,
            "organisation" => $organization,
        ]);

        $result = $response->LogonResult;

        // Check response is successful
        if ($result !== self::CHANGE_OK) {
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

    /**
     * Sets the current company in the API
     *
     * @param Office $office
     * @return bool
     */
    public function setOffice(Office $office): bool
    {
        $result = $this->SelectCompany(
            ['company' => $office->getCode()]
        );

        return self::CHANGE_OK === $result->SelectCompanyResult;
    }

    final protected function WSDL(): string
    {
        return "/webservices/session.asmx?wsdl";
    }
};