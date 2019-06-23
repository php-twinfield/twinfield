<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\ArticleApiConnector;
use PhpTwinfield\ApiConnectors\InvoiceApiConnector;
use PhpTwinfield\ApiConnectors\InvoiceTypeApiConnector;
use PhpTwinfield\Article;
use PhpTwinfield\Customer;
use PhpTwinfield\Currency;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\InvoiceVatLine;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

/**
 * @covers Invoice
 * @covers InvoiceLine
 * @covers InvoiceTotals
 * @covers InvoiceVatLine
 * @covers InvoicesDocument
 * @covers InvoiceMapper
 * @covers InvoiceApiConnector
 */
class InvoiceIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var InvoiceApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $invoiceApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->invoiceApiConnector = new InvoiceApiConnector($this->connection);
        
        $mockInvoiceTypeApiConnector = \Mockery::mock(InvoiceTypeApiConnector::class)->makePartial();
        $mockInvoiceTypeApiConnector->shouldReceive('getInvoiceTypeVatType')->andReturnUsing(function() {
            return 'exclusive';
        });
 
        $mockArticleApiConnector = \Mockery::mock(ArticleApiConnector::class)->makePartial();
        $mockArticleApiConnector->shouldReceive('get')->andReturnUsing(function() {
            $article = new Article;
            $article->setAllowChangeVatCode(true);
            return $article;
        });
    }
    
    protected function tearDown()
    {
        parent::setUp();
        
        unset($mockInvoiceTypeApiConnector, $mockArticleApiConnector);
    }

    public function testGetConceptInvoiceWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/invoiceConceptGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Invoice::class))
            ->willReturn($response);

        $invoice = $this->invoiceApiConnector->get('FACTUUR', '5', Office::fromCode('001'));

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Office::fromCode("11024"), $invoice->getOffice());
        $this->assertSame('FACTUUR', Util::objectToStr($invoice->getInvoiceType()));
        $this->assertSame('5', $invoice->getInvoiceNumber());
        $this->assertSame('20120831', Util::formatDate($invoice->getInvoiceDate()));
        $this->assertSame('BNK', Util::objectToStr($invoice->getBank()));
        $this->assertSame(1, $invoice->getInvoiceAddressNumber());
        $this->assertSame(1, $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', Util::objectToStr($invoice->getCustomer()));
        $this->assertSame('2012/08', $invoice->getPeriod());
        $this->assertSame('EUR', Util::objectToStr($invoice->getCurrency()));
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\InvoiceStatus');
        $this->assertSame($ReflectObject->getConstant('CONCEPT'), (string)$invoice->getStatus());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PaymentMethod');
        $this->assertSame($ReflectObject->getConstant('CASH'), (string)$invoice->getPaymentMethod());
        $this->assertSame('HEADER', $invoice->getHeaderText());
        $this->assertSame('FOOTER', $invoice->getFooterText());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey(0, $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines[0];

        $this->assertSame(1, $invoiceLine->getID());
        $this->assertSame('0', Util::objectToStr($invoiceLine->getArticle()));
        $this->assertSame('118', $invoiceLine->getSubArticleToString());
        $this->assertSame(1.0, $invoiceLine->getQuantity());
        $this->assertSame(1, $invoiceLine->getUnits());
        $this->assertSame(true, $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame('15.00', Util::formatMoney($invoiceLine->getValueExcl()));
        $this->assertSame('0.00', Util::formatMoney($invoiceLine->getVatValue()));
        $this->assertSame('15.00', Util::formatMoney($invoiceLine->getValueInc()));
        $this->assertSame('15.00', Util::formatMoney($invoiceLine->getUnitsPriceExcl()));
        $this->assertSame('8020', Util::objectToStr($invoiceLine->getDim1()));

        $invoiceVatLines = $invoice->getVatLines();
        $this->assertCount(1, $invoiceVatLines);

        /** @var InvoiceVatLine $invoiceVatLine */
        $invoiceVatLine = current($invoiceVatLines);

        $this->assertSame('VN', Util::objectToStr($invoiceLine->getVatCode()));
        $this->assertSame('0.00', Util::formatMoney($invoiceLine->getVatValue()));
        $this->assertNull($invoiceLine->getPerformanceType());
        $this->assertNull($invoiceLine->getPerformanceDate());

        $this->assertSame('15.00', Util::formatMoney($invoice->getTotals()->getValueInc()));
        $this->assertSame('15.00', Util::formatMoney($invoice->getTotals()->getValueExcl()));

        $this->assertNull($invoice->getFinancialNumber());
        $this->assertNull($invoice->getFinancialCode());
    }

    public function testGetFinalInvoiceWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/invoiceFinalGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Invoice::class))
            ->willReturn($response);

        $invoice = $this->invoiceApiConnector->get('FACTUUR', '5', Office::fromCode('001'));

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Office::fromCode("11024"), $invoice->getOffice());
        $this->assertSame('FACTUUR', Util::objectToStr($invoice->getInvoiceType()));
        $this->assertSame('5', $invoice->getInvoiceNumber());
        $this->assertSame('20120831', Util::formatDate($invoice->getInvoiceDate()));
        $this->assertSame('BNK', Util::objectToStr($invoice->getBank()));
        $this->assertSame(1, $invoice->getInvoiceAddressNumber());
        $this->assertSame(1, $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', Util::objectToStr($invoice->getCustomer()));
        $this->assertSame('2012/08', $invoice->getPeriod());
        $this->assertSame('EUR', Util::objectToStr($invoice->getCurrency()));
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\InvoiceStatus');
        $this->assertSame($ReflectObject->getConstant('FINAL'), (string)$invoice->getStatus());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PaymentMethod');
        $this->assertSame($ReflectObject->getConstant('CASH'), (string)$invoice->getPaymentMethod());
        $this->assertSame('HEADER', $invoice->getHeaderText());
        $this->assertSame('FOOTER', $invoice->getFooterText());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey(0, $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines[0];

        $this->assertSame(1, $invoiceLine->getID());
        $this->assertSame('0', Util::objectToStr($invoiceLine->getArticle()));
        $this->assertSame('118', $invoiceLine->getSubArticleToString());
        $this->assertSame(1.0, $invoiceLine->getQuantity());
        $this->assertSame(1, $invoiceLine->getUnits());
        $this->assertSame(true, $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame('15.00', Util::formatMoney($invoiceLine->getValueExcl()));
        $this->assertSame('0.00', Util::formatMoney($invoiceLine->getVatValue()));
        $this->assertSame('15.00', Util::formatMoney($invoiceLine->getValueInc()));
        $this->assertSame('15.00', Util::formatMoney($invoiceLine->getUnitsPriceExcl()));
        $this->assertSame('8020', Util::objectToStr($invoiceLine->getDim1()));

        $invoiceVatLines = $invoice->getVatLines();
        $this->assertCount(1, $invoiceVatLines);

        /** @var InvoiceVatLine $invoiceVatLine */
        $invoiceVatLine = current($invoiceVatLines);

        $this->assertSame('VN', Util::objectToStr($invoiceLine->getVatCode()));
        $this->assertSame('0.00', Util::formatMoney($invoiceLine->getVatValue()));
        $this->assertNull($invoiceLine->getPerformanceType());
        $this->assertNull($invoiceLine->getPerformanceDate());

        $this->assertSame('15.00', Util::formatMoney($invoice->getTotals()->getValueInc()));
        $this->assertSame('15.00', Util::formatMoney($invoice->getTotals()->getValueExcl()));

        $this->assertSame(123456789, $invoice->getFinancialNumber());
        $this->assertSame('123456789', $invoice->getFinancialCode());
    }

    public function testSendInvoiceWorks()
    {      
        $customer = new Customer();
        $customer->setCode('1000');

        $invoice = new Invoice();
        $invoice->setOffice(Office::fromCode('11024'));
        $invoice->setInvoiceType(\PhpTwinfield\InvoiceType::fromCode('FACTUUR'));
        $invoice->setInvoiceNumber('5');
        $invoice->setInvoiceDate(Util::parseDate('20120831'));
        $invoice->setBank(\PhpTwinfield\CashBankBook::fromCode('BNK'));
        $invoice->setInvoiceAddressNumber(1);
        $invoice->setDeliverAddressNumber(1);
        $invoice->setCustomer($customer);
        $invoice->setPeriod('2012/08');
        $invoice->setCurrency(Currency::fromCode('EUR'));
        $invoice->setStatus(\PhpTwinfield\Enums\InvoiceStatus::CONCEPT());
        $invoice->setPaymentMethod(\PhpTwinfield\Enums\PaymentMethod::CASH());
        $invoice->setHeaderText('HEADER');
        $invoice->setFooterText('FOOTER');

        $invoiceLine = new InvoiceLine();
        $invoiceLine->setID(1);
        $invoiceLine->setArticle(\PhpTwinfield\Article::fromCode('4'));
        $invoiceLine->setSubArticle(\PhpTwinfield\ArticleLine::fromCode('118'));
        $invoiceLine->setQuantity(1);
        $invoiceLine->setUnits(1);
        $invoiceLine->setAllowDiscountOrPremium(true);
        $invoiceLine->setDescription('CoalesceFunctioningOnImpatienceTShirt');
        $invoiceLine->setValueExcl(Util::parseMoney(15.00, new \Money\Currency('EUR')));
        $invoiceLine->setVatValue(Util::parseMoney(0.00, new \Money\Currency('EUR')));
        $invoiceLine->setValueInc(Util::parseMoney(15.00, new \Money\Currency('EUR')));
        $invoiceLine->setUnitsPriceExcl(Util::parseMoney(15.00, new \Money\Currency('EUR')));
        $invoiceLine->setDim1(\PhpTwinfield\GeneralLedger::fromCode('8020'));
        $invoiceLine->setVatCode(\PhpTwinfield\VatCode::fromCode('VN'));
        $invoice->addLine($invoiceLine);

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(InvoicesDocument::class))
            ->willReturnCallback(function (InvoicesDocument $invoicesDocument): Response {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(__DIR__ . '/resources/invoiceSendRequest.xml'),
                    $invoicesDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->invoiceApiConnector->send($invoice);
    }

    protected function getSuccessfulResponse(): Response
    {
        return Response::fromString(
            '<salesinvoices result="1">
                <salesinvoice result="1" />
            </salesinvoices>'
        );
    }
}
