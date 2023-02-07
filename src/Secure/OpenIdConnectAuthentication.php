<?php

namespace PhpTwinfield\Secure;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use PhpTwinfield\Office;
use PhpTwinfield\Secure\Provider\InvalidAccessTokenException;
use PhpTwinfield\Secure\Provider\OAuthException;
use PhpTwinfield\Secure\Provider\OAuthProvider;

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
     * @var AccessTokenInterface|null
     */
    private $accessToken;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var Office
     */
    private $office;

    /**
     * @var null|string
     */
    private $cluster = null;

    /**
     * @var array
     */
    private $afterValidateCallbacks = [];

    /**
     * @var bool
     */
    private $hasValidatedAccessToken = false;

    /**
     * The office code that is part of the Office object that is passed here will be
     * the default office code used during requests. If an office code is included in
     * the SOAP request body, this will always take precedence over this default.
     *
     * Please note that for most calls an office is mandatory. If you do not supply it
     * you have to pass it with every request, or call setOffice.
     */
    public function __construct(
        OAuthProvider $provider,
        string $refreshToken,
        ?Office $office = null,
        ?AccessTokenInterface $accessToken = null
    )
    {
        $this->provider     = $provider;
        $this->refreshToken = $refreshToken;
        $this->office       = $office;
        $this->accessToken  = $accessToken;
    }

    public function setOffice(?Office $office)
    {
        $this->resetAllClients();
        $this->office = $office;
    }

    protected function getCluster(): ?string
    {
        return $this->cluster;
    }

    protected function setCluster(?string $cluster): self
    {
        $this->cluster = $cluster;
        return $this;
    }

    /**
     * @throws InvalidAccessTokenException
     */
    protected function getSoapHeaders(): \SoapHeader
    {
        $this->throwExceptionMissingAccessToken();

        $headers = [
            "AccessToken" => $this->getAccessToken()->getToken(),
        ];

        // Watch out. When you don't supply an Office and do an authenticated call you will get an
        // exception from Twinfield saying 'Toegang geweigerd. Company ontbreekt in request header.'
        if ($this->office !== null) {
            $headers["CompanyCode"] = $this->office->getCode();
        }

        return new \SoapHeader(
            'http://www.twinfield.com/',
            'Header',
            $headers
        );
    }

    /**
     * @throws OAuthException
     * @throws InvalidAccessTokenException
     */
    protected function login(): void
    {
        // Refresh the token when it's not set or is set, but expired or incomplete.
        if (!$this->hasAccessToken() || $this->isExpiredAccessToken()) {
            $this->refreshToken();
        }

        // There's no need to validate the access token if it's already validated and still valid.
        if (!$this->hasValidatedAccessToken()) {
            $validationResult = $this->validateToken();
            $this->setCluster($validationResult["twf.clusterUrl"]);
        }
    }

    public function hasValidatedAccessToken(): bool
    {
        return $this->hasValidatedAccessToken;
    }

    protected function setValidatedAccessToken(bool $validated = true): void
    {
        $this->hasValidatedAccessToken = $validated;
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
        $this->setValidatedAccessToken(false);
        $this->throwExceptionMissingAccessToken();

        $accessToken = $this->getAccessToken();
        $validationResult = $this->getValidationResult($accessToken);
        $this->callAfterValidateCallbacks($accessToken);

        $resultDecoded = \json_decode($validationResult, true);
        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new OAuthException("Error while decoding JSON: " . \json_last_error_msg());
        }

        return $resultDecoded;
    }

    /**
     * @throws InvalidAccessTokenException
     */
    protected function throwExceptionMissingAccessToken(): void
    {
        if (!$this->hasAccessToken()) {
            throw new InvalidAccessTokenException("Missing access token.");
        }
    }

    /**
     * @throws InvalidAccessTokenException
     */
    protected function getValidationResult(AccessTokenInterface $accessToken): string
    {
        $validationUrl    = "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=";
        $validationResult = @file_get_contents($validationUrl . urlencode($accessToken->getToken()));

        if ($validationResult === false) {
            throw new InvalidAccessTokenException("Access token is invalid.");
        }

        return $validationResult;
    }

    /**
     * @throws OAuthException
     */
    protected function refreshToken(): void
    {
        // If you pass an empty refresh token, it will try to derive it from the accessToken object
        // If that is set.
        $refreshToken = !empty($this->refreshToken)
            ? $this->refreshToken
            : ($this->hasAccessToken()
                ? $this->getAccessToken()->getRefreshToken() ?? null
                : null
            );

        try {
            $accessToken = $this->provider->getAccessToken(
                "refresh_token",
                ["refresh_token" => $refreshToken]
            );
        } catch (IdentityProviderException $e) {
            throw new OAuthException($e->getMessage(), 0, $e);
        }

        $this->setAccessToken($accessToken);
    }

    /**
     * Validate if there's an access token, and it's not expired.
     * Will return true when there's no access token or expired is not set.
     *
     * @return bool
     */
    protected function isExpiredAccessToken(): bool
    {
        $accessToken = $this->getAccessToken();
        if ($accessToken instanceof AccessTokenInterface) {
            try {
                return $accessToken->hasExpired();
            }
            catch (\Exception $e) {}
        }

        return true;
    }

    /**
     * @return AccessTokenInterface|null
     */
    public function getAccessToken(): ?AccessTokenInterface
    {
        return $this->accessToken;
    }

    public function setAccessToken(?AccessTokenInterface $accessToken): self
    {
        $this->setValidatedAccessToken(false);
        $this->accessToken = $accessToken;

        return $this;
    }

    public function hasAccessToken(): bool
    {
        return $this->getAccessToken() instanceof AccessTokenInterface;
    }

    /**
     * Register callbacks that will be invoked with the accessToken after a new access token is fetched.
     * You may use this callback to store the acquired access token.
     *
     * Be aware, this access token grants access to the entire twinfield administration.
     * Please store it in a safe place, preferable encrypted.
     *
     * @param  callable  $callable
     * @return $this
     */
    public function registerAfterValidateCallback(callable $callable): self
    {
        $this->afterValidateCallbacks[] = $callable;

        return $this;
    }

    protected function getAfterValidateCallbacks(): array
    {
        return $this->afterValidateCallbacks;
    }

    protected function callAfterValidateCallbacks(AccessTokenInterface $accessToken): void
    {
        $callbacks = $this->getAfterValidateCallbacks();

        if (!empty($callbacks)) {
            foreach ($callbacks as $callback) {
                $callback($accessToken);
            }
        }
    }
}