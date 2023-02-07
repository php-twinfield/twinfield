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

    protected function setUp(): void
    {
        $this->openIdProvider = $this->getMockBuilder(OAuthProvider::class)
            ->setMethods([ "getAccessToken" ])
            ->getMock();
    }

    public function testSetOfficeAndResetAuthenticatedClients()
    {
        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, "refresh", null ])
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

    /**
     * When instantiating the class the accessToken property is still null. The flow should be:
     * -- login ->
     *      access_token = null     -> getRefreshToken
     * -- validateToken
     */
    public function testRefreshAccessTokenWhenAccessTokenIsNullAndLoginIsSuccessful()
    {
        $this->openIdProvider->expects($this->once())
            ->method("getAccessToken")
            ->willReturn(new AccessToken([
                "access_token"  =>  "stub",
            ]));

        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, "refresh", null ])
            ->setMethods(["validateToken"])
            ->getMock();

        $openIdConnect->expects($this->once())
            ->method("validateToken")
            ->willReturn(
                ["twf.clusterUrl" => "someClusterUrl"]
            );

        $openIdConnect = Liberator::liberate($openIdConnect);
        $this->assertNull($openIdConnect->getAccessToken());

        $openIdConnect->login();

        $this->assertEquals("stub", $openIdConnect->getAccessToken());
        $this->assertEquals("someClusterUrl", $openIdConnect->getCluster());
    }

    public function testRefreshAndAccessTokenAreSetLoginSuccess()
    {
        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, "refresh", null, new AccessToken([
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
            ->setConstructorArgs([ $this->openIdProvider, "refresh", null ])
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
        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, 'test');

        $result = $openIdConnect->setAccessToken($accessTokenMock);

        $this->assertEquals($accessTokenMock, $openIdConnect->getAccessToken());
        $this->assertInstanceOf(OpenIdConnectAuthentication::class, $result);
    }

    public function testGetAccessTokenSetFromConstructor(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $openIdConnect = new OpenIdConnectAuthentication(
            $this->openIdProvider,
            'test',
            null,
            $accessTokenMock
        );

        $this->assertEquals($accessTokenMock, $openIdConnect->getAccessToken());
    }

    public function testHasAccessToken(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $openIdConnect = new OpenIdConnectAuthentication(
            $this->openIdProvider,
            'test',
            null,
            $accessTokenMock
        );

        $this->assertTrue($openIdConnect->hasAccessToken());
    }

    public function testHasNoAccessToken(): void
    {
        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, 'test');

        $this->assertFalse($openIdConnect->hasAccessToken());
    }

    public function testIsExpiredAccessTokenWithoutToken(): void
    {
        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, 'test');

        $openIdConnect = Liberator::liberate($openIdConnect);
        $result = $openIdConnect->isExpiredAccessToken();

        $this->assertTrue($result);
    }

    public function testIsExpiredAccessTokenWithToken(): void
    {
        $accessTokenMock = $this->createMock(AccessTokenInterface::class);
        $accessTokenMock->expects($this->once())
            ->method('hasExpired')
            ->willReturn(true);

        $openIdConnect = new OpenIdConnectAuthentication(
            $this->openIdProvider,
            'test',
            null,
            $accessTokenMock
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
            'test',
            null,
            $accessTokenMock
        );

        $openIdConnect = Liberator::liberate($openIdConnect);
        $result = $openIdConnect->isExpiredAccessToken();

        $this->assertFalse($result);
    }

    public function testRegisterAfterValidateCallback(): void
    {
        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, 'test');
        $openIdConnect->registerAfterValidateCallback(function() {});

        $openIdConnect = Liberator::liberate($openIdConnect);
        $callbacks = $openIdConnect->getAfterValidateCallbacks();

        $this->assertCount(1, $callbacks);
        $this->assertInstanceOf(Closure::class, $callbacks[0]);
    }

    public function testValidateTokenWithMissingAccessToken(): void
    {
        $this->expectException(InvalidAccessTokenException::class);

        $openIdConnect = new OpenIdConnectAuthentication($this->openIdProvider, 'test');
        $openIdConnect = Liberator::liberate($openIdConnect);
        $openIdConnect->validateToken();
    }

    public function testCallAfterValidateCallbacks(): void
    {
        $accessToken = $this->createMock(AccessTokenInterface::class);

        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([$this->openIdProvider, "refresh", null, $accessToken])
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
