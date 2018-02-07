<?php

namespace PhpTwinfield\Secure;

use League\OAuth2\Client\Token\AccessToken;
use PhpTwinfield\Office;
use PhpTwinfield\Secure\Provider\OAuthProvider;
use PhpTwinfield\Secure\Provider\OAuthException;

/**
 * This class allows you to authenticate with an access token to the Twinfield APIs.
 *
 * @see OAuthProvider for retrieving an access code.
 */
class OpenIdConnectAuthentication extends AuthenticatedConnection
{
    /**
     * @var OAuthProvider
     */
    private $provider;

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var Office
     */
    private $office;

    /**
     * @var string
     */
    private $cluster;

    /**
     * @throws OAuthException
     */
    public function __construct(OAuthProvider $provider, AccessToken $accessToken, Office $office)
    {
        $this->provider    = $provider;
        $this->accessToken = $accessToken;

        /*
         * The office code that is part of the Office object that is passed here will be
         * the default office code used during requests. If an office code is included in
         * the SOAP request body, this will always take precedence over this default.
         */
        $this->office      = $office;

        if (!$accessToken->getRefreshToken()) {
            throw new OAuthException("AccessToken does not contain a refresh token.");
        }
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
                'CompanyCode' => $this->office->getCode()
            ]
        );
    }

    /**
     * @throws OAuthException
     */
    protected function login(): void
    {
        $validationResult = $this->validateToken();
        if ($validationResult === false) {
            $this->refreshToken();
            $validationResult = $this->validateToken();
        }

        $this->cluster = $validationResult["twf.clusterUrl"];
    }

    /**
     * @throws OAuthException
     *
     * @return array|bool
     */
    protected function validateToken()
    {
        $validationUrl    = "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=";
        try {
            $validationResult = @file_get_contents($validationUrl . urlencode($this->accessToken));
        } catch (\Exception $e) {
            throw new OAuthException("Could not validate access token: {$e->getMessage()}");
        }

        return json_decode($validationResult, true);
    }

    protected function refreshToken(): void
    {
        $this->accessToken = $this->provider->getAccessToken(
            "refresh_token",
            ["refresh_token" => $this->accessToken->getRefreshToken()]
        );
    }
}