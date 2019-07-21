<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\ActivityApiConnector;
use PhpTwinfield\Activity;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class ActivityApiConnectorTest extends TestCase
{
    /**
     * @var ActivityApiConnector
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

        $this->apiConnector = new ActivityApiConnector($connection);
    }

    private function createActivity(): Activity
    {
        $activity = new Activity();
        return $activity;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/activity-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $activity = $this->createActivity();

        $mapped = $this->apiConnector->send($activity);

        $this->assertInstanceOf(Activity::class, $mapped);
        $this->assertEquals("A001", $mapped->getCode());
        $this->assertEquals("Test Activity", $mapped->getName());
    }
}
