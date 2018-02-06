<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\BaseApiConnector;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class BaseApiConnectorTest extends TestCase
{
    /**
     * @var AuthenticatedConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $connection;

    /**
     * @var BaseApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    protected function setUp()
    {
        parent::setUp();

        $this->connection = $this->getMockBuilder(AuthenticatedConnection::class)
            ->disableOriginalConstructor()
            ->setMethods(["getAuthenticatedClient", "resetClient"])
            ->getMockForAbstractClass();

        $this->service = $this->getMockBuilder(BaseApiConnector::class)
            ->setConstructorArgs([$this->connection])
            ->getMockForAbstractClass();
    }

    public function testXmlDocumentIsResentWhenLogonErrorOccurs()
    {
        $client = $this->getMockBuilder(ProcessXmlService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->connection->expects($this->exactly(2))
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($client);

        $this->connection->expects($this->once())
            ->method("resetClient")
            ->with(Services::PROCESSXML())
            ->willReturn($client);

        $soapFault = new \SoapFault("xx", "[soap:Client] Your logon credentials are not valid anymore. Try to log on again.", "client");

        $response = Response::fromString('<dimension result="1" />');

        $client->expects($this->exactly(2))
            ->method("sendDocument")
            ->will($this->onConsecutiveCalls(
                $this->throwException($soapFault),
                $this->returnValue($response)
            ));

        $this->assertEquals($response, $this->service->sendXmlDocument(new \DOMDocument()));
    }
}