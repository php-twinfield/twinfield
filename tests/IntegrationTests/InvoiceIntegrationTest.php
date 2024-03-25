<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\InvoiceApiConnector;
use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\Invoice;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;

/**
 * @covers Invoice
 * @covers InvoiceLine
 * @covers InvoiceTotals
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->invoiceApiConnector = new InvoiceApiConnector($this->connection);
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
        $this->assertSame('FACTUUR', $invoice->getInvoiceType());
        $this->assertSame('5', $invoice->getInvoiceNumber());
        $this->assertSame('20120831', $invoice->getInvoiceDate());
        $this->assertSame('BNK', $invoice->getBank());
        $this->assertSame('1', $invoice->getInvoiceAddressNumber());
        $this->assertSame('1', $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomer()->getCode());
        $this->assertSame('2012/8', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrency());
        $this->assertSame('concept', $invoice->getStatus());
        $this->assertSame('cash', $invoice->getPaymentMethod());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame('1', $invoiceLine->getID());
        $this->assertSame('0', $invoiceLine->getArticle());
        $this->assertSame('118', $invoiceLine->getSubArticle());
        $this->assertSame('1', $invoiceLine->getQuantity());
        $this->assertSame('1', $invoiceLine->getUnits());
        $this->assertSame('true', $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame('15.00', $invoiceLine->getValueExcl());
        $this->assertSame('0.00', $invoiceLine->getVatValue());
        $this->assertSame('15.00', $invoiceLine->getValueInc());
        $this->assertSame('15.00', $invoiceLine->getUnitsPriceExcl());
        $this->assertSame('8020', $invoiceLine->getDim1());
        $this->assertSame('VN', $invoiceLine->getVatCode());

        // TODO - Vat lines

        $this->assertSame('15.00', $invoice->getTotals()->getValueInc());
        $this->assertSame('15.00', $invoice->getTotals()->getValueExcl());

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
        $this->assertSame('FACTUUR', $invoice->getInvoiceType());
        $this->assertSame('5', $invoice->getInvoiceNumber());
        $this->assertSame('20120831', $invoice->getInvoiceDate());
        $this->assertSame('BNK', $invoice->getBank());
        $this->assertSame('1', $invoice->getInvoiceAddressNumber());
        $this->assertSame('1', $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomer()->getCode());
        $this->assertSame('2012/8', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrency());
        $this->assertSame('final', $invoice->getStatus());
        $this->assertSame('cash', $invoice->getPaymentMethod());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame('1', $invoiceLine->getID());
        $this->assertSame('0', $invoiceLine->getArticle());
        $this->assertSame('118', $invoiceLine->getSubArticle());
        $this->assertSame('1', $invoiceLine->getQuantity());
        $this->assertSame('1', $invoiceLine->getUnits());
        $this->assertSame('true', $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame('15.00', $invoiceLine->getValueExcl());
        $this->assertSame('0.00', $invoiceLine->getVatValue());
        $this->assertSame('15.00', $invoiceLine->getValueInc());
        $this->assertSame('15.00', $invoiceLine->getUnitsPriceExcl());
        $this->assertSame('8020', $invoiceLine->getDim1());

        // TODO - Vat lines

        $this->assertSame('15.00', $invoice->getTotals()->getValueInc());
        $this->assertSame('15.00', $invoice->getTotals()->getValueExcl());

        $this->assertSame('123456789', $invoice->getFinancialNumber());
        $this->assertSame('123456789', $invoice->getFinancialCode());
    }

    public function testSendInvoiceWorks()
    {
        $customer = new Customer();
        $customer->setCode('1000');

        $invoice = new Invoice();
        $invoice->setOffice(Office::fromCode('11024'));
        $invoice->setInvoiceType('FACTUUR');
        $invoice->setInvoiceNumber('5');
        $invoice->setInvoiceDate('20120831');
        $invoice->setBank('BNK');
        $invoice->setInvoiceAddressNumber('1');
        $invoice->setDeliverAddressNumber('1');
        $invoice->setCustomer($customer);
        $invoice->setPeriod('2012/8');
        $invoice->setCurrency('EUR');
        $invoice->setStatus('concept');
        $invoice->setPaymentMethod('cash');

        $invoiceLine = new InvoiceLine();
        $invoiceLine->setID('1');
        $invoiceLine->setArticle('4');
        $invoiceLine->setSubArticle('118');
        $invoiceLine->setQuantity('1');
        $invoiceLine->setUnits('1');
        $invoiceLine->setAllowDiscountOrPremium('true');
        $invoiceLine->setDescription('CoalesceFunctioningOnImpatienceTShirt');
        $invoiceLine->setValueExcl('15.00');
        $invoiceLine->setVatValue('0.00');
        $invoiceLine->setValueInc('15.00');
        $invoiceLine->setUnitsPriceExcl('15.00');
        $invoiceLine->setDim1('8020');
        $invoiceLine->setVatCode('VN');
        $invoice->addLine($invoiceLine);

        $totals = new InvoiceTotals();
        $totals->setValueExcl('15.00');
        $totals->setValueInc('15.00');
        $invoice->setTotals($totals);

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
