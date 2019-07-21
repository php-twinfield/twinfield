<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\ArticleApiConnector;
use PhpTwinfield\ApiConnectors\InvoiceApiConnector;
use PhpTwinfield\ApiConnectors\InvoiceTypeApiConnector;
use PhpTwinfield\Article;
use PhpTwinfield\Invoice;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PhpTwinfield\Util;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InvoiceApiConnectorTest extends TestCase
{
    /**
     * @var InvoiceApiConnector
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

        $this->apiConnector = new InvoiceApiConnector($connection);

        $mockInvoiceTypeApiConnector = \Mockery::mock('overload:'.InvoiceTypeApiConnector::class)->makePartial();
        $mockInvoiceTypeApiConnector->shouldReceive('getInvoiceTypeVatType')->andReturnUsing(function() {
            return 'exclusive';
        });

        $mockArticleApiConnector = \Mockery::mock('overload:'.ArticleApiConnector::class)->makePartial();
        $mockArticleApiConnector->shouldReceive('get')->andReturnUsing(function() {
            $article = new Article;
            $article->setAllowChangeVatCode(true);
            return $article;
        });
    }

    private function createInvoice(): Invoice
    {
        $invoice = new Invoice();
        return $invoice;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/invoice-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $invoice = $this->createInvoice();

        $mapped = $this->apiConnector->send($invoice);

        $this->assertInstanceOf(Invoice::class, $mapped);
        $this->assertEquals("10", $mapped->getInvoiceNumber());
        $this->assertEquals("20190410", Util::formatDate($mapped->getInvoiceDate()));
    }
}
