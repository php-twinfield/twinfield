<?php

namespace PhpTwinfield\UnitTests\Secure;

use Closure;
use Eloquent\Liberator\Liberator;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use PhpTwinfield\Office;
use PhpTwinfield\Secure\OpenIdConnectAuthentication;
use PhpTwinfield\Secure\Provider\InvalidAccessTokenException;
use PhpTwinfield\Secure\Provider\OAuthProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenIdConnectionAuthenticationTest extends TestCase
{
    /**
     * @var OAuthProvider | MockObject
     */
    private $openIdProvider;

    /**
     * @var AccessToken
     */
    private $accessToken;

    protected function setUp(): void
    {
        $this->openIdProvider = $this->getMockBuilder(OAuthProvider::class)
            ->setMethods([ "getAccessToken" ])
            ->getMock();

        $this->accessToken = new AccessToken([
            'access_token'  => 'foo',
            'refresh_token' => 'bar',
            'expires_in'    => 1000,
        ]);
    }

    public function testSetOfficeAndResetAuthenticatedClients()
    {
        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, $this->accessToken, null ])
            ->setMethods([ "resetAllClients" ])
            ->getMock();

        $openIdConnect->expects($this->once())
            ->method("resetAllClients");

        $office = Office::fromCode("001");
        $openIdConnect = Liberator::liberate($openIdConnect);

        $this->assertNull($openIdConnect->office);
        $openIdConnect->setOffice($office);

        $this->assertEquals($office, $openIdConnect->office);
        $this->assertEmpty($openIdConnect->authenticatedClients);
    }

    public function testRefreshAndAccessTokenAreSetLoginSuccess()
    {
        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, $this->accessToken, null, new AccessToken([
                'access_token' => 'test',
                'expires_in' => time() + 1000,
            ]) ])
            ->setMethods(["validateToken", "refreshToken"])
            ->getMock();

        $openIdConnect->expects($this->once())
            ->method("validateToken")
            ->willReturn(
                ["twf.clusterUrl" => "someClusterUrl"]
            );

        $openIdConnect->expects($this->never())
            ->method("refreshToken");

        $openIdConnect = Liberator::liberate($openIdConnect);

        $openIdConnect->login();

        $this->assertEquals("someClusterUrl", $openIdConnect->getCluster());
    }

    public function testInvalidTokenLogin()
    {
        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, $this->accessToken, null ])
            ->setMethods(["validateToken", "refreshToken"])
            ->getMock();

        $openIdConnect->expects($this->once())
            ->method("validateToken")
            ->willThrowException(new InvalidAccessTokenException())
            ->willReturn(
                ["twf.clusterUrl" => "someClusterUrl"]
            );

        $openIdConnect = Liberator::liberate($openIdConnect);
        $openIdConnect->login();

        $this->assertEquals("someClusterUrl", $openIdConnect->getCluster());
    }

    public function testSetAccessToken(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, $accessTokenMock);

        $result = $openIdConnect->setAccessToken($accessTokenMock);

        $this->assertEquals($accessTokenMock, $openIdConnect->getAccessToken());
        $this->assertInstanceOf(OpenIdConnectAuthentication::class, $result);
    }

    public function testGetAccessTokenSetFromConstructor(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $openIdConnect = new OpenIdConnectAuthentication(
            $this->openIdProvider,
            $accessTokenMock,
            null,
        );

        $this->assertEquals($accessTokenMock, $openIdConnect->getAccessToken());
    }

    public function testIsExpiredAccessToken(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $accessTokenMock->expects($this->once())
            ->method('hasExpired')
            ->willReturn(true);

        $openIdConnect = new OpenIdConnectAuthentication(
            $this->openIdProvider,
            $accessTokenMock,
            null,
        );

        $openIdConnect = Liberator::liberate($openIdConnect);
        $result = $openIdConnect->isExpiredAccessToken();

        $this->assertTrue($result);
    }

    public function testIsNotExpired(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $accessTokenMock->expects($this->once())
            ->method('hasExpired')
            ->willReturn(false);

        $openIdConnect = new OpenIdConnectAuthentication(
            $this->openIdProvider,
            $accessTokenMock,
            null,
        );

        $openIdConnect = Liberator::liberate($openIdConnect);
        $result = $openIdConnect->isExpiredAccessToken();

        $this->assertFalse($result);
    }

    public function testRegisterAfterValidateCallback(): void
    {
        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, $this->accessToken);
        $openIdConnect->registerAfterValidateCallback(function() {});

        $openIdConnect = Liberator::liberate($openIdConnect);

        $this->assertCount(1, $openIdConnect->afterValidateCallbacks);
        $this->assertInstanceOf(Closure::class, $openIdConnect->afterValidateCallbacks[0]);
    }

    public function testValidateTokenWithMissingAccessToken(): void
    {
        $this->expectException(InvalidAccessTokenException::class);

        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, $this->accessToken);
        $openIdConnect = Liberator::liberate($openIdConnect);
        $openIdConnect->validateToken();
    }

    public function testCallAfterValidateCallbacks(): void
    {
        $accessToken = $this->createMock(AccessTokenInterface::class);

        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([$this->openIdProvider, $accessToken, null])
            ->setMethods(["getValidationResult"])
            ->getMock();
        $openIdConnect->expects($this->once())
            ->method('getValidationResult')
            ->willReturn(json_encode(['test']));

        $callableMock = $this->getMockBuilder('DoesNotExists')
            ->setMockClassName('Foo')
            ->setMethods(['callback'])
            ->getMock();
        $callableMock->expects($this->once())
            ->method('callback')
            ->with($accessToken);

        $openIdConnect->registerAfterValidateCallback([$callableMock, 'callback']);

        $openIdConnect = Liberator::liberate($openIdConnect);
        $openIdConnect->validateToken();
    }
}
