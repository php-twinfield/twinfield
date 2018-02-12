<?php

namespace PhpTwinfield\Secure;

use League\OAuth2\Client\Token\AccessToken;
use PhpTwinfield\Office;
use PhpTwinfield\Secure\Provider\InvalidAccessTokenException;
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
        try {
            $validationResult = $this->validateToken();
        } catch (InvalidAccessTokenException $e) {
            $this->refreshToken();
            $validationResult = $this->validateToken();
        }

        $this->cluster = $validationResult["twf.clusterUrl"];
    }

    /**
     * Validate the OAuth2 access token. If an access token is deemed valid, Twinfield returns a JSON
     * object containing information about the access token. If it is invalid, Twinfield returns a
     * status 400, in which case 'file_get_contents' returns false.
     *
     * @throws OAuthException
     * @throws InvalidAccessTokenException
     */
    protected function validateToken(): array
    {
        $validationUrl    = "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=";
        try {
            $validationResult = @file_get_contents($validationUrl . urlencode($this->accessToken));
            if ($validationResult === false) {
                throw new InvalidAccessTokenException("Access token is invalid.");
            }
        } catch (\Exception $e) {
            throw new OAuthException("Could not validate access token: {$e->getMessage()}");
        }

        $resultDecoded = json_decode($validationResult, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new OAuthException("Error while decoding JSON: " . json_last_error_msg());
        }
        return $resultDecoded;
    }

    protected function refreshToken(): void
    {
        $this->accessToken = $this->provider->getAccessToken(
            "refresh_token",
            ["refresh_token" => $this->accessToken->getRefreshToken()]
        );
    }
}