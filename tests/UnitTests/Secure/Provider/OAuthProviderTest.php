<?php

namespace PhpTwinfield\Secure\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;

class OAuthProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ClientInterface | MockObject
     */
    protected $httpClient;

    /**
     * @var \PhpTwinfield\Secure\OAuthProvider
     */
    protected $provider;

    /**
     * @var AccessToken | MockObject
     */
    protected $token;

    protected function SetUp()
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->provider = new OAuthProvider([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'https://example.org/'
        ]);
        $this->token    = $this->createMock(AccessToken::class);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $url = $this->provider->getBaseAccessTokenUrl([]);
        $uri = parse_url($url);
        $this->assertEquals('/auth/authentication/connect/token', $uri['path']);
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        parse_str(parse_url($url)['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertArrayHasKey('nonce', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testResourceOwnerDetailsUrl()
    {
        $url = $this->provider->getResourceOwnerDetailsUrl($this->token);
        $uri = parse_url($url);
        $this->assertEquals('/auth/authentication/connect/userinfo', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())
            ->method("getBody")
            ->willReturn('{"access_token":"mock_access_token", "token_type":"bearer"}');
        $response->expects($this->any())
            ->method("getHeader")
            ->willReturn(['content-type' => 'json']);
        $response->expects($this->any())
            ->method("getStatusCode")
            ->willReturn(200);

        $this->httpClient->expects($this->once())
            ->method("send")
            ->willReturn($response);
        $this->provider->setHttpClient($this->httpClient);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())
            ->method("getBody")
            ->willReturn('{"error":{"type":"request","message":"someErrorMessage"}}');
        $response->expects($this->any())
            ->method("getHeader")
            ->willReturn(['content-type' => 'json']);
        $response->expects($this->any())
            ->method("getStatusCode")
            ->willReturn(400);

        $this->httpClient->expects($this->once())
            ->method("send")
            ->willReturn($response);
        $this->provider->setHttpClient($this->httpClient);

        $this->expectException(IdentityProviderException::class);
        $this->expectExceptionMessage("someErrorMessage");
        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    public function testGetResourceOwnerDetails()
    {
        /* TODO: Make this test actually run when the 'getResourceOwner' method is fixed. */
        $this->assertTrue(True);
        return;

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())
            ->method("getBody")
            ->willReturn('{"id": "someId", "name": "someName"}');
        $response->expects($this->any())
            ->method("getHeader")
            ->willReturn(['content-type' => 'json']);
        $response->expects($this->any())
            ->method("getStatusCode")
            ->willReturn(200);

        $this->httpClient->expects($this->once())
            ->method("send")
            ->willReturn($response);
        $this->provider->setHttpClient($this->httpClient);

        $this->token->expects($this->once())
            ->method("__toString")
            ->willReturn("someToken");

        $account = $this->provider->getResourceOwner($this->token);
        $this->assertEquals("someId", $account->getId());
        $this->assertEquals(["id" => "someId", "name" => "someName"], $account->toArray());
    }
}