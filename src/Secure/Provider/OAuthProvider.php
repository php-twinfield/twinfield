<?php

namespace PhpTwinfield\Secure\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Authentication/OpenIdConnect
 */
class OAuthProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * PLEASE NOTE: This scope is for some reason not actually supported by Twinfield. If this scope is included in
     * the authorization url, visiting it results in an 'invalid_scope' error message.
     *
     * twf.user (Identity token) - contains Twinfield specific information about the user. This scope contains the following claims:
     * - twf.id - contains GUID of Twinfield ID. Only if you have the availability of a Twinfield ID.
     * - twf.organisationUserCode - user code inside organisation
     * - twf.organisationId - GUID of organisation
     */
    public const SCOPE_USER = 'twf.user';

    /**
     * twf.organisation - contains organisation information. This scope contains the following claims:
     * - twf.organisationCode - code of organisation
     * - twf.organisationId - GUID of organisation
     * - twf.clusterUrl - URL of cluster on which organisation is located
     */
    public const SCOPE_ORGANISATION = 'twf.organisation';

    /**
     * twf.organisationUser - contains information about organisation user. This scope contains the following claims:
     * - twf.organisationUserCode - code of the organisation user (used for login)
     * - twf.organisationCode - code of the organisation
     * - twf.organisationId - GUID of organisation
     *
     * Note that the twf.organisationUser scope is mandatory in order to login.
     */
    public const SCOPE_ORGANISATION_USER = 'twf.organisationUser';

    /**
     * @var string
     */
    protected $nonce;

    /**
     * Returns the base URL for authorizing a client.
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://login.twinfield.com/auth/authentication/connect/authorize';
    }

    /**
     * Returns the base URL for requesting an access token.
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://login.twinfield.com/auth/authentication/connect/token';
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://login.twinfield.com/auth/authentication/connect/userinfo';
    }

    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details of the resource owner, rather than all
     * the available scopes.
     */
    protected function getDefaultScopes(): array
    {
        return [self::SCOPE_ORGANISATION_USER];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * Returns authorization parameters based on provided options.
     * @throws OAuthException
     */
    protected function getAuthorizationParameters(array $options): array
    {
        /**
         * The 'SCOPE_USER' scope is not actually supported by Twinfield.
         * @see OAuthProvider::SCOPE_USER
         */
        if (array_key_exists("scope", $options) && in_array(self::SCOPE_USER, $options["scope"])) {
            throw new OAuthException("Scope '" . self::SCOPE_USER . "' is not supported by Twinfield.");
        }
        $options = parent::getAuthorizationParameters($options);

        /* Add a random 'nonce', as required by Twinfield. */
        $this->nonce = $this->getRandomState();
        $options["nonce"] = $this->nonce;

        return $options;
    }

    /**
     * Checks a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() < 400) {
            return;
        }

        /* An error has occurred; format the exception. */
        $message = $response->getReasonPhrase();
        if (isset($data["error"])) {
            if (isset($data['error']['type']) && isset($data['error']['message'])) {
                $message = "[{$data['error']['type']}] {$data['error']['message']}";
            } else {
                $message = $data["error"];
            }

            if (isset($data["error"]["field"])) {
                $message .= " ({$data['error']['field']})";
            }
        }

        throw new IdentityProviderException($message, $response->getStatusCode(), $response);
    }

    /**
     * @throws \BadMethodCallException
     */
    public function getResourceOwner(AccessToken $token): void
    {
        throw new \BadMethodCallException("This method has not been implemented");
    }

    /**
     * Generates a resource owner object from a successful resource owner details request.
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new ResourceOwner($response);
    }
}