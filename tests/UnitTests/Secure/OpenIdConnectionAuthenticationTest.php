<?php

namespace PhpTwinfield\UnitTests\Secure;

use Eloquent\Liberator\Liberator;
use PhpTwinfield\Secure\OpenIdConnectAuthentication;
use PhpTwinfield\Secure\Provider\InvalidAccessTokenException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenIdConnectionAuthenticationTest extends TestCase
{
    /**
     * @var OpenIdConnectAuthentication | MockObject
     */
    private $openIdConnect;

    protected function SetUp()
    {
        $this->openIdConnect = $this->getMockBuilder(OpenIdConnectAuthentication::class)
            ->disableOriginalConstructor()
            ->setMethods(["validateToken", "refreshToken"])
            ->getMock();
    }

    public function testValidTokenLogin()
    {
        $this->openIdConnect->expects($this->once())
            ->method("validateToken")
            ->willReturn(["twf.clusterUrl" => "someClusterUrl"]);

        $openIdConnect = Liberator::liberate($this->openIdConnect);
        $openIdConnect->login();
        $this->assertEquals("someClusterUrl", $openIdConnect->getCluster());
    }

    public function testInvalidTokenLogin()
    {
        $this->openIdConnect->expects($this->exactly(2))
            ->method("validateToken")
            ->willReturn(
                $this->throwException(new InvalidAccessTokenException()),
                ["twf.clusterUrl" => "someClusterUrl"]
            );
        $this->openIdConnect->expects($this->once())
            ->method("refreshToken");

        $openIdConnect = Liberator::liberate($this->openIdConnect);
        $openIdConnect->login();
        $this->assertEquals("someClusterUrl", $openIdConnect->getCluster());
    }
}