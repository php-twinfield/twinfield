<?php

namespace PhpTwinfield\Secure;

/**
 * The connection class is used for communicating with the main Twinfield objects.
 */
class Connection
{
    /**
     * OAuth provider.
     */
    private $provider;

    /**
     * Base URL to the Twinfield authentication URL's (with trailing slash).
     */
    private const BASEURL = 'https://login.twinfield.com/auth/authentication/connect/';

    /**
     * @var
     */
    private $twinfieldClientId;

    /**
     * @var
     */
    private $twinfieldClientSecret;

    /**
     * @var
     */
    private $redirectUrl;

    /**
     * @var
     */
    private $authCode = null;

    /**
     * @var
     */
    private $authToken = null;

    /**
     * @var
     */
    private $authRefreshToken = null;

    /**
     * @var
     */
    private $authExpires = null;

    /**
     * @var
     */
    private $scopes = ['openid', 'twf.user', 'twf.organisation', 'twf.organisationUser'];

    /**
     * Set the OAuth provider.
     */
    private function initProvider()
    {
        if ($this->provider !== null) {
            return;
        }

        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'redirectUri' => $this->redirectUrl,
            'scopes' => $this->scopes,
            'scopeSeparator' => '+',
            'urlAuthorize' => self::BASEURL.'authorize',
            'urlAccessToken' => self::BASEURL.'token',
            'urlResourceOwnerDetails' => self::BASEURL.'userinfo',
        ]);
    }

    /**
     * Set refresh token timestamp expiry.
     */
    public function setTokenExpires($refreshtoken)
    {
        $this->authExpires = $refreshtoken;
    }

    /**
     * Set refresh token information.
     */
    public function setRefreshToken($refreshtoken)
    {
        $this->authRefreshToken = $refreshtoken;
    }

    /**
     * Set access token information.
     */
    public function setAccessToken($token)
    {
        $this->authToken = $token;
    }

    /**
     * Set the code for exchange.
     */
    public function setAuthorizationCode($code)
    {
        $this->authCode = $code;
    }

    /**
     * Set the client id.
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Set the client id.
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Set redirect URL.
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Get the provider to work with directly (for instance if you don't want to use the header method for redirection).
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Should it refresh based on current expiry.
     */
    private function needsAuthentication()
    {
        if ($this->authRefreshToken && $this->authToken) {
            // since we already have a token
            return true;
        } else {
            // no token / refresh token
            return false;
        }
    }

    /**
     * Has the current token expired?
     */
    public function tokenHasExpired()
    {
        if (empty($this->authExpires)) {
            return true;
        }

        return $this->authExpires <= (time() + 30); // 30 sec margin for twinfield
    }

    /**
     * Get the authenticated clients for usage.
     */
    public function getAuthenticatedClient($services)
    {
        var_dump('XXX');
        var_dump($services);

        die();
    }

    /**
     * Connects or refreshes a token.
     */
    public function connect()
    {
        if (!$this->needsAuthentication()) {
            // already connected and no need to refresh
            return true;
        }

        // If access token is not set or token has expired, acquire new token
        if ($this->authToken === null || $this->tokenHasExpired()) {
            return $this->acquireAccessToken();
        }

        return true;
    }

    /**
     * 
     */
    public function prepareRequest()
    {
        // check if we need to refresh before adding the header
        if ($this->authToken === null || $this->tokenHasExpired()) {
            $this->acquireAccessToken();
        }
    }

    /**
     * Exchange the code for an access token or refresh.
     */
    private function acquireAccessToken()
    {
        try {
            // setup provider
            $this->initProvider();
            // check if we need to refresh
            if (empty($this->authRefreshToken)) {
                // get a new token
                $accessToken = $this->getProvider()->getAccessToken('authorization_code', [
                    'code' => $this->authCode,
                ]);
            } else {
                // refresh token
                $accessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $this->authRefreshToken,
                ]);
            }
            // set to object
            $this->authToken = $accessToken->getToken();
            $this->authRefreshToken = $accessToken->getRefreshToken();
            $this->authExpires = $accessToken->getExpires();
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // failed to get the access token or user details.
            throw new \PhpTwinfield\Exception('Failed to exchange the code for an access token ('.$e->getMessage().')');
        }
    }

    /**
     * Redirects the user to the authentication URL for approving access to Twinfield.
     */
    public function redirectForAuthorization()
    {
        // setup provider
        $this->initProvider();
        // redirect to twinfield for authentication        
        header('Location: '.$this->getProvider()->getAuthorizationUrl());
    }
}
