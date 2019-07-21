<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\VatCodeApiConnector;
use PhpTwinfield\VatCode;
use PhpTwinfield\VatCodePercentage;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class VatCodeApiConnectorTest extends TestCase
{
    /**
     * @var VatCodeApiConnector
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

        $this->apiConnector = new VatCodeApiConnector($connection);
    }

    private function createVatCode(): VatCode
    {
        $vatCode = new VatCode();
        $vatCodePercentage = new VatCodePercentage();
        $vatCodePercentage->setDate(\PhpTwinfield\Util::parseDate('20121001'));
        $vatCode->addPercentage($vatCodePercentage);
        return $vatCode;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/vatcode-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $vatCode = $this->createVatCode();

        $mapped = $this->apiConnector->send($vatCode);

        $this->assertInstanceOf(VatCode::class, $mapped);
        $this->assertEquals("VH", $mapped->getCode());
        $this->assertEquals("VAT Sales High", $mapped->getName());
    }
}
