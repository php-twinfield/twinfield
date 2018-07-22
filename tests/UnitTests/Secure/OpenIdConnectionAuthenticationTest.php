<?php

namespace PhpTwinfield\UnitTests\Secure;

use Eloquent\Liberator\Liberator;
use League\OAuth2\Client\Token\AccessToken;
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

    protected function SetUp()
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
        $this->assertNull($openIdConnect->accessToken);

        $openIdConnect->login();

        $this->assertEquals("stub", $openIdConnect->accessToken);
        $this->assertEquals("someClusterUrl", $openIdConnect->getCluster());
    }

    public function testRefreshAndAccessTokenAreSetLoginSuccess()
    {
        $openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->setConstructorArgs([ $this->openIdProvider, "refresh", null ])
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
        $openIdConnect->accessToken = "test";

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
}