<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\AssetMethodApiConnector;
use PhpTwinfield\AssetMethod;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class AssetMethodApiConnectorTest extends TestCase
{
    /**
     * @var AssetMethodApiConnector
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

        $this->apiConnector = new AssetMethodApiConnector($connection);
    }

    private function createAssetMethod(): AssetMethod
    {
        $assetMethod = new AssetMethod();
        return $assetMethod;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/assetmethod-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $assetMethod = $this->createAssetMethod();

        $mapped = $this->apiConnector->send($assetMethod);

        $this->assertInstanceOf(AssetMethod::class, $mapped);
        $this->assertEquals("101", $mapped->getCode());
        $this->assertEquals("Inventaris", $mapped->getName());
    }
}
