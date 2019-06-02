<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\FixedAssetApiConnector;
use PhpTwinfield\FixedAsset;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class FixedAssetApiConnectorTest extends TestCase
{
    /**
     * @var FixedAssetApiConnector
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

        $this->apiConnector = new FixedAssetApiConnector($connection);
    }

    private function createFixedAsset(): FixedAsset
    {
        $fixedAsset = new FixedAsset();
        return $fixedAsset;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/fixedasset-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $fixedAsset = $this->createFixedAsset();

        $mapped = $this->apiConnector->send($fixedAsset);

        $this->assertInstanceOf(FixedAsset::class, $mapped);
        $this->assertEquals("60000", $mapped->getCode());
        $this->assertEquals("Afschrijving Computer", $mapped->getName());
    }
}
