<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\CostCenterApiConnector;
use PhpTwinfield\CostCenter;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class CostCenterApiConnectorTest extends TestCase
{
    /**
     * @var CostCenterApiConnector
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

        $this->apiConnector = new CostCenterApiConnector($connection);
    }

    private function createCostCenter(): CostCenter
    {
        $costCenter = new CostCenter();
        return $costCenter;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/costcenter-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $costCenter = $this->createCostCenter();

        $mapped = $this->apiConnector->send($costCenter);

        $this->assertInstanceOf(CostCenter::class, $mapped);
        $this->assertEquals("00001", $mapped->getCode());
        $this->assertEquals("Hoevelaken", $mapped->getName());
    }
}
