<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\SupplierApiConnector;
use PhpTwinfield\Supplier;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class SupplierApiConnectorTest extends TestCase
{
    /**
     * @var SupplierApiConnector
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

        $this->apiConnector = new SupplierApiConnector($connection);
    }

    private function createSupplier(): Supplier
    {
        $supplier = new Supplier();
        return $supplier;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/supplier-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $supplier = $this->createSupplier();

        $mapped = $this->apiConnector->send($supplier);

        $this->assertInstanceOf(Supplier::class, $mapped);
        $this->assertEquals("2000", $mapped->getCode());
        $this->assertEquals("Smits", $mapped->getName());
    }
}
