<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\BaseApiConnector;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Connection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class BaseApiConnectorTest extends TestCase
{
    /**
     * @var Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $connection;

    /**
     * @var BaseApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    protected function setUp()
    {
        parent::setUp();

        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(["getAuthenticatedClient", "resetClient"])
            ->getMock();

        $this->service = $this->getMockBuilder(BaseApiConnector::class)
            ->setConstructorArgs([$this->connection])
            ->getMockForAbstractClass();
    }

    public function soapFaultProvider(): array
    {
        return array_map(function($message) {
            return [new \SoapFault("xx", "[soap:Client] {$message}", "client")];
        }, $this->getResendErrorMessages());
    }

    public function errorExceptionProvider(): array
    {
        return array_map(function($message) {
            return [new \ErrorException("[soap:Client] {$message}")];
        }, $this->getResendErrorMessages());
    }

    private function getResendErrorMessages(): array
    {
        return (new \ReflectionClassConstant(
            BaseApiConnector::class,
            "RETRY_REQUEST_EXCEPTION_MESSAGES"
        ))->getValue();
    }

    /**
     * @dataProvider soapFaultProvider
     * @dataProvider errorExceptionProvider
     */
    public function testXmlDocumentIsResentWhenMatchingException(\Exception $exception)
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

        $response = Response::fromString('<dimension result="1" />');

        $client->expects($this->exactly(2))
            ->method("sendDocument")
            ->will($this->onConsecutiveCalls(
                $this->throwException($exception),
                $this->returnValue($response)
            ));

        $this->assertEquals($response, $this->service->sendXmlDocument(new \DOMDocument()));
    }
}