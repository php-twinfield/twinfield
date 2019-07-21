<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\DimensionGroupApiConnector;
use PhpTwinfield\DimensionGroup;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class DimensionGroupApiConnectorTest extends TestCase
{
    /**
     * @var DimensionGroupApiConnector
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

        $this->apiConnector = new DimensionGroupApiConnector($connection);
    }

    private function createDimensionGroup(): DimensionGroup
    {
        $dimensionGroup = new DimensionGroup();
        return $dimensionGroup;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/dimensiongroup-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $dimensionGroup = $this->createDimensionGroup();

        $mapped = $this->apiConnector->send($dimensionGroup);

        $this->assertInstanceOf(DimensionGroup::class, $mapped);
        $this->assertEquals("TSTDIMGRP", $mapped->getCode());
        $this->assertEquals("Test Dimension Group", $mapped->getName());
    }
}
