<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\DimensionTypeApiConnector;
use PhpTwinfield\DimensionType;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class DimensionTypeApiConnectorTest extends TestCase
{
    /**
     * @var DimensionTypeApiConnector
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

        $this->apiConnector = new DimensionTypeApiConnector($connection);
    }

    private function createDimensionType(): DimensionType
    {
        $dimensionType = new DimensionType();
        return $dimensionType;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/dimensiontype-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $dimensionType = $this->createDimensionType();

        $mapped = $this->apiConnector->send($dimensionType);

        $this->assertInstanceOf(DimensionType::class, $mapped);
        $this->assertEquals("DEB", $mapped->getCode());
        $this->assertEquals("Debiteuren", $mapped->getName());
    }
}
