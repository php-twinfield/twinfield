<?php

namespace PhpTwinfield\Secure;

use PhpTwinfield\Exception;
use League\OAuth2\Client\Token\AccessToken;

/**
 * This class allows you to authenticate with an access token to the Twinfield APIs.
 *
 * @see OAuthProvider for retrieving an access code.
 */
class OpenIdConnectAuthentication extends AuthenticatedConnection
{
    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var string
     */
    private $cluster;

    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    protected function getCluster(): ?string
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
        if (null === $this->cluster) {

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
            $validationResult = json_decode($validation, true);
            $this->cluster = $validationResult["twf.clusterUrl"];
        }
    }
}