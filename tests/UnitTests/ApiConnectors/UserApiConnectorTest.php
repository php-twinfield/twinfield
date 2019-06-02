<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\UserApiConnector;
use PhpTwinfield\User;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class UserApiConnectorTest extends TestCase
{
    /**
     * @var UserApiConnector
     */
    protected $apiConnector;

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processXmlService;

    protected function setUp()
    {
        parent::setUp();

        $this->processXmlService = $this->getMockBuilder(ProcessXmlService::class)
            ->setMethods(["sendDocument"])
            ->disableOriginalConstructor()
            ->getMock();

        /** @var AuthenticatedConnection|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->createMock(AuthenticatedConnection::class);
        $connection
            ->expects($this->any())
            ->method("getAuthenticatedClient")
            ->willReturn($this->processXmlService);

        $this->apiConnector = new UserApiConnector($connection);
    }

    private function createUser(): User
    {
        $user = new User();
        return $user;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/user-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $user = $this->createUser();

        $mapped = $this->apiConnector->send($user);

        $this->assertInstanceOf(User::class, $mapped);
        $this->assertEquals("API000001", $mapped->getCode());
        $this->assertEquals("Test User", $mapped->getName());
    }
}
