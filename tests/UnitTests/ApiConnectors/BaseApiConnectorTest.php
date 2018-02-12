<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\BaseApiConnector;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
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

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

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

        $this->client = $this->getMockBuilder(ProcessXmlService::class)
            ->disableOriginalConstructor()
            ->getMock();
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

    private function getMaxNumRetries(): int
    {
        $reflectionConstant = new \ReflectionClassConstant(BaseApiConnector::class,"MAX_RETRIES");
        return $reflectionConstant->getValue();
    }

    /**
     * @dataProvider soapFaultProvider
     * @dataProvider errorExceptionProvider
     */
    public function testXmlDocumentIsResentWhenMatchingException(\Exception $exception)
    {
        $this->connection->expects($this->exactly(2))
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($this->client);

        $this->connection->expects($this->once())
            ->method("resetClient")
            ->with(Services::PROCESSXML());

        $response = Response::fromString('<dimension result="1" />');

        $this->client->expects($this->exactly(2))
            ->method("sendDocument")
            ->will($this->onConsecutiveCalls(
                $this->throwException($exception),
                $this->returnValue($response)
            ));

        $this->assertEquals($response, $this->service->sendXmlDocument(new \DOMDocument()));
        $numRetries = $this->getObjectAttribute($this->service, "numRetries");
        $this->assertEquals(0, $numRetries);
    }

    /**
     * @dataProvider soapFaultProvider
     * @dataProvider errorExceptionProvider
     */
    public function testResendXmlDocumentStopsAfterMaxRetries(\Exception $exception)
    {
        $this->connection->expects($this->exactly($this->getMaxNumRetries() + 1))
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($this->client);

        $this->connection->expects($this->exactly($this->getMaxNumRetries() + 1))
            ->method("resetClient")
            ->with(Services::PROCESSXML());

        $this->client->expects($this->exactly($this->getMaxNumRetries() + 1))
            ->method("sendDocument")
            ->willThrowException($exception);

        $this->expectException(Exception::class);
        $this->service->sendXmlDocument(new \DOMDocument());

        $numRetries = $this->getObjectAttribute($this->service, "numRetries");
        $this->assertEquals(0, $numRetries);
    }
}