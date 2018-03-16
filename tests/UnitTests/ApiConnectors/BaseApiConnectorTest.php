<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\BaseApiConnector;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class BaseApiConnectorTest extends TestCase implements LoggerInterface
{
    use LoggerTrait;

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

    /**
     * @var mixed[][]
     */
    private $logs;

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

    public function testSendDocumentLogsRequestAndResponse()
    {
        $request_document = new \DOMDocument();
        $request_document->loadXML('<dimension>value</dimension>');

        $response = Response::fromString('<dimension result="1">value</dimension>');

        $this->connection->expects($this->any())
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($this->client);

        $this->client->expects($this->any())
            ->method("sendDocument")
            ->willReturn($response);

        $this->service->setLogger($this);
        $this->service->sendXmlDocument($request_document);

        self::assertCount(2, $this->logs);

        [$level, $message, $context] = $this->logs[0];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Sending request to Twinfield.', $message);
        self::assertSame($this->completeXml('<dimension>value</dimension>'), $context['document_xml']);

        [$level, $message, $context] = $this->logs[1];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Received response from Twinfield.', $message);
        self::assertSame($this->completeXml('<dimension result="1">value</dimension>'), $context['document_xml']);
    }

    private function completeXml(string $xml): string
    {
        return sprintf(
            '<?xml version="1.0"?>%s%s%s',
            \PHP_EOL,
            $xml,
            \PHP_EOL
        );
    }

    /**
     * Used to keep track of the logs created by the API connector
     *
     * @inheritdoc
     */
    public function log($level, $message, array $context = array())
    {
        $this->logs[] = [$level, $message, $context];
    }

    /**
     * @dataProvider soapFaultProvider
     * @dataProvider errorExceptionProvider
     */
    public function testSendDocumentLogsRetries(\Throwable $e)
    {
        $request_document = new \DOMDocument();
        $request_document->loadXML('<dimension>value</dimension>');

        $response = Response::fromString('<dimension result="1">value</dimension>');

        $this->connection->expects($this->any())
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($this->client);

        $this->client->expects($this->any())
            ->method("sendDocument")
            ->will($this->onConsecutiveCalls(
                $this->throwException($e),
                $this->throwException($e),
                $this->returnValue($response)
            ));

        $this->service->setLogger($this);
        $this->service->sendXmlDocument($request_document);


        self::assertCount(6, $this->logs);

        [$level, $message, $context] = $this->logs[0];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Sending request to Twinfield.', $message);
        self::assertSame($this->completeXml('<dimension>value</dimension>'), $context['document_xml']);

        [$level, $message, $context] = $this->logs[1];
        self::assertSame(LogLevel::INFO, $level);
        self::assertSame("Retrying request. Reason for initial failure: {$e->getMessage()}", $message);
        self::assertEmpty($context);

        [$level, $message, $context] = $this->logs[2];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Sending request to Twinfield. (attempt 2)', $message);
        self::assertSame($this->completeXml('<dimension>value</dimension>'), $context['document_xml']);

        [$level, $message, $context] = $this->logs[3];
        self::assertSame(LogLevel::INFO, $level);
        self::assertSame("Retrying request. Reason for initial failure: {$e->getMessage()}", $message);
        self::assertEmpty($context);
        self::assertEmpty($context);

        [$level, $message, $context] = $this->logs[4];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Sending request to Twinfield. (attempt 3)', $message);
        self::assertSame($this->completeXml('<dimension>value</dimension>'), $context['document_xml']);

        [$level, $message, $context] = $this->logs[5];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Received response from Twinfield.', $message);
        self::assertSame($this->completeXml('<dimension result="1">value</dimension>'), $context['document_xml']);
    }

    public function testSendDocumentLogsFailures()
    {
        $request_document = new \DOMDocument();
        $request_document->loadXML('<dimension>value</dimension>');

        $this->connection->expects($this->any())
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($this->client);

        $this->client->expects($this->any())
            ->method("sendDocument")
            ->will($this->onConsecutiveCalls(
                $this->throwException(new \SoapFault('Server', 'Internal server error'))
            ));

        $this->service->setLogger($this);
        try {
            $this->service->sendXmlDocument($request_document);
        } catch (\PhpTwinfield\Exception $e) {
        }

        self::assertCount(2, $this->logs);

        [$level, $message, $context] = $this->logs[0];
        self::assertSame(LogLevel::DEBUG, $level);
        self::assertSame('Sending request to Twinfield.', $message);
        self::assertSame($this->completeXml('<dimension>value</dimension>'), $context['document_xml']);

        [$level, $message, $context] = $this->logs[1];
        self::assertSame(LogLevel::ERROR, $level);
        self::assertSame("Request to Twinfield failed: Internal server error", $message);
        self::assertEmpty($context);
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