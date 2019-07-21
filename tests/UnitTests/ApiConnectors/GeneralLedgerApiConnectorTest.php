<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\GeneralLedgerApiConnector;
use PhpTwinfield\GeneralLedger;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class GeneralLedgerApiConnectorTest extends TestCase
{
    /**
     * @var GeneralLedgerApiConnector
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

        $this->apiConnector = new GeneralLedgerApiConnector($connection);
    }

    private function createGeneralLedger(): GeneralLedger
    {
        $generalLedger = new GeneralLedger();
        return $generalLedger;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/generalledger-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $generalLedger = $this->createGeneralLedger();

        $mapped = $this->apiConnector->send($generalLedger);

        $this->assertInstanceOf(GeneralLedger::class, $mapped);
        $this->assertEquals("4004", $mapped->getCode());
        $this->assertEquals("Bonussen/gratificaties", $mapped->getName());
    }
}
