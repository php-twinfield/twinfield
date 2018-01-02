<?php

namespace PhpTwinfield\Secure;

use PhpTwinfield\Exception;

/**
 * This class allows you to authenticate with an access token to the Twinfield APIs.
 *
 * Now how you get an access token - todo.
 */
class OpenIdConnectAuthentication extends AuthenticatedConnection
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $cluster;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    protected function getCluster(): string
    {
        return $this->cluster;
    }

    protected function getSoapHeaders()
    {
        return new \SoapHeader(
            'http://www.twinfield.com/',
            'Header',
            [
                'AccessToken' => $this->accessToken,
            ]
        );
    }

    /**
     * @throws Exception
     */
    protected function login(): void
    {
        if (null !== $this->cluster) {

            $validation = file_get_contents(
                "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=".urlencode(
                    $this->accessToken
                )
            );

            if (false === $validation) {
                throw new Exception("Error validating access token.");
            }

            /*
             * In order to determine the correct cluster the retrieved access token should be sent to the
             * accesstokenvalidation endpoint. Part of the response is the twf.clusterUrl.
             */
            $validationResult = json_decode($validation); // todo validate that this works.

            $this->cluster = $validationResult->twf->clusterUrl;
        }
    }
}