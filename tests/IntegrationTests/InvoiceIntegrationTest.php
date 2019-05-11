<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\ArticleApiConnector;
use PhpTwinfield\ApiConnectors\InvoiceApiConnector;
use PhpTwinfield\ApiConnectors\InvoiceTypeApiConnector;
use PhpTwinfield\Article;
use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\InvoiceVatLine;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;

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
        
        $mockInvoiceTypeApiConnector = \Mockery::mock('overload:'.InvoiceTypeApiConnector::class);
        $mockInvoiceTypeApiConnector->shouldReceive('getInvoiceTypeVatType')->andReturnUsing(function() {
            return 'exclusive';
        });
 
        $mockArticleApiConnector = \Mockery::mock('overload:'.ArticleApiConnector::class);
        $mockArticleApiConnector->shouldReceive('get')->andReturnUsing(function() {
            $article = new Article;
            $article->setAllowChangeVatCode(true);
            return $article;
        });
    }

    public function testGetConceptInvoiceWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/invoiceConceptGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Invoice::class))
            ->willReturn($response);

        $invoice = $this->invoiceApiConnector->get('FACTUUR', '5', $this->office);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Office::fromCode("11024"), $invoice->getOffice());
        $this->assertSame('FACTUUR', $invoice->getInvoiceTypeToString());
        $this->assertSame(5, $invoice->getInvoiceNumber());
        $this->assertSame('20120831', $invoice->getInvoiceDateToString());
        $this->assertSame('BNK', $invoice->getBankToString());
        $this->assertSame(1, $invoice->getInvoiceAddressNumber());
        $this->assertSame(1, $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomerToString());
        $this->assertSame('2012/08', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrencyToString());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\InvoiceStatus');
        $this->assertSame($ReflectObject->getConstant('CONCEPT'), (string)$invoice->getStatus());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PaymentMethod');
        $this->assertSame($ReflectObject->getConstant('CASH'), (string)$invoice->getPaymentMethod());
        $this->assertSame('HEADER', $invoice->getHeaderText());
        $this->assertSame('FOOTER', $invoice->getFooterText());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame(1, $invoiceLine->getID());
        $this->assertSame('0', $invoiceLine->getArticleToString());
        $this->assertSame('118', $invoiceLine->getSubArticleToString());
        $this->assertSame(1, $invoiceLine->getQuantity());
        $this->assertSame(1, $invoiceLine->getUnits());
        $this->assertSame(true, $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame(15.00, $invoiceLine->getValueExclToFloat());
        $this->assertSame(0.00, $invoiceLine->getVatValueToFloat());
        $this->assertSame(15.00, $invoiceLine->getValueIncToFloat());
        $this->assertSame(15.00, $invoiceLine->getUnitsPriceExclToFloat());
        $this->assertSame('8020', $invoiceLine->getDim1ToString());

        $invoiceVatLines = $invoice->getVatLines();
        $this->assertCount(1, $invoiceVatLines);

        /** @var InvoiceVatLine $invoiceVatLine */
        $invoiceVatLine = current($invoiceVatLines);

        $this->assertSame('VN', $invoiceLine->getVatCodeToString());
        $this->assertSame(0.00, $invoiceLine->getVatValueToFloat());
        $this->assertNull($invoiceLine->getPerformanceType());
        $this->assertNull($invoiceLine->getPerformanceDateToString());

        $this->assertSame(15.00, $invoice->getTotals()->getValueIncToFloat());
        $this->assertSame(15.00, $invoice->getTotals()->getValueExclToFloat());

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

        $invoice = $this->invoiceApiConnector->get('FACTUUR', '5', $this->office);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Office::fromCode("11024"), $invoice->getOffice());
        $this->assertSame('FACTUUR', $invoice->getInvoiceTypeToString());
        $this->assertSame(5, $invoice->getInvoiceNumber());
        $this->assertSame('20120831', $invoice->getInvoiceDateToString());
        $this->assertSame('BNK', $invoice->getBankToString());
        $this->assertSame(1, $invoice->getInvoiceAddressNumber());
        $this->assertSame(1, $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomerToString());
        $this->assertSame('2012/08', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrencyToString());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\InvoiceStatus');
        $this->assertSame($ReflectObject->getConstant('FINAL'), (string)$invoice->getStatus());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PaymentMethod');
        $this->assertSame($ReflectObject->getConstant('CASH'), (string)$invoice->getPaymentMethod());
        $this->assertSame('HEADER', $invoice->getHeaderText());
        $this->assertSame('FOOTER', $invoice->getFooterText());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame(1, $invoiceLine->getID());
        $this->assertSame('0', $invoiceLine->getArticleToString());
        $this->assertSame('118', $invoiceLine->getSubArticleToString());
        $this->assertSame(1, $invoiceLine->getQuantity());
        $this->assertSame(1, $invoiceLine->getUnits());
        $this->assertSame(true, $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame(15.00, $invoiceLine->getValueExclToFloat());
        $this->assertSame(0.00, $invoiceLine->getVatValueToFloat());
        $this->assertSame(15.00, $invoiceLine->getValueIncToFloat());
        $this->assertSame(15.00, $invoiceLine->getUnitsPriceExclToFloat());
        $this->assertSame('8020', $invoiceLine->getDim1ToString());

        $invoiceVatLines = $invoice->getVatLines();
        $this->assertCount(1, $invoiceVatLines);

        /** @var InvoiceVatLine $invoiceVatLine */
        $invoiceVatLine = current($invoiceVatLines);

        $this->assertSame('VN', $invoiceLine->getVatCodeToString());
        $this->assertSame(0.00, $invoiceLine->getVatValueToFloat());
        $this->assertNull($invoiceLine->getPerformanceType());
        $this->assertNull($invoiceLine->getPerformanceDateToString());

        $this->assertSame(15.00, $invoice->getTotals()->getValueIncToFloat());
        $this->assertSame(15.00, $invoice->getTotals()->getValueExclToFloat());

        $this->assertSame(123456789, $invoice->getFinancialNumber());
        $this->assertSame('123456789', $invoice->getFinancialCode());
    }

    public function testSendInvoiceWorks()
    {      
        $customer = new Customer();
        $customer->setCode('1000');

        $invoice = new Invoice();
        $invoice->setOffice(Office::fromCode('11024'));
        $invoice->setInvoiceTypeFromString('FACTUUR');
        $invoice->setInvoiceNumber(5);
        $invoice->setInvoiceDateFromString('20120831');
        $invoice->setBankFromString('BNK');
        $invoice->setInvoiceAddressNumber(1);
        $invoice->setDeliverAddressNumber(1);
        $invoice->setCustomer($customer);
        $invoice->setPeriod('2012/08');
        $invoice->setCurrencyFromString('EUR');
        $invoice->setStatusFromString('concept');
        $invoice->setPaymentMethodFromString('cash');
        $invoice->setHeaderText('HEADER');
        $invoice->setFooterText('FOOTER');

        $invoiceLine = new InvoiceLine();
        $invoiceLine->setID(1);
        $invoiceLine->setArticleFromString('4');
        $invoiceLine->setSubArticleFromString('118');
        $invoiceLine->setQuantity(1);
        $invoiceLine->setUnits(1);
        $invoiceLine->setAllowDiscountOrPremiumFromString('true');
        $invoiceLine->setDescription('CoalesceFunctioningOnImpatienceTShirt');
        $invoiceLine->setValueExclFromFloat(15.00);
        $invoiceLine->setVatValueFromFloat(0.00);
        $invoiceLine->setValueIncFromFloat(15.00);
        $invoiceLine->setUnitsPriceExclFromFloat(15.00);
        $invoiceLine->setDim1FromString('8020');
        $invoiceLine->setVatCodeFromString('VN');
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
