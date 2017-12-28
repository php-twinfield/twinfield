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
     * Exchange the code for an access token.
     */
    public function getAccessToken($code)
    {
        try {
            // setup provider
            $this->initProvider();
            // Try to get an access token using the authorization code grant.
            $accessToken = $this->getProvider()->getAccessToken('authorization_code', [
                'code' => $code,
            ]);
            // We have an access token, which we may use in authenticated
            // requests against the service provider's API.
            echo 'Access Token: '.$accessToken->getToken().'<br>';
            echo 'Refresh Token: '.$accessToken->getRefreshToken().'<br>';
            echo 'Expired in: '.$accessToken->getExpires().'<br>';
            echo 'Already expired? '.($accessToken->hasExpired() ? 'expired' : 'not expired').'<br>';
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
