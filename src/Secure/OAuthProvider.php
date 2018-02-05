<?php

namespace PhpTwinfield\Secure;

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
     * Checks a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        print_r($response);
        print_r($data);

        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException('TODO', 0, 'TODO');
        }
    }

    /**
     * Generates a resource owner object from a successful resource owner details request.
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new ResourceOwner($response);
    }
}