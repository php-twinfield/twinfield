<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\InvoiceApiConnector;
use PhpTwinfield\Customer;
use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Invoice;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Connection;
use PhpTwinfield\Secure\Service;

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

    protected function setUp()
    {
        parent::setUp();

        $this->invoiceApiConnector = new InvoiceApiConnector($this->login);
    }

    public function testGetInvoiceWorks()
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(realpath(__DIR__ . '/resources/invoiceGetResponse.xml')));
        $response = new Response($domDocument);

        $this->client
            ->expects($this->once())
            ->method("sendDOMDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Invoice::class))
            ->willReturn($response);

        $invoice = $this->invoiceApiConnector->get('FACTUUR', '5', $this->office);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertSame('11024', $invoice->getOffice());
        $this->assertSame('FACTUUR', $invoice->getInvoiceType());
        $this->assertSame('5', $invoice->getInvoiceNumber());
        $this->assertEquals(new \DateTimeImmutable('2012-08-31'), $invoice->getInvoiceDate());
        $this->assertSame('BNK', $invoice->getBank());
        $this->assertSame('1', $invoice->getInvoiceAddressNumber());
        $this->assertSame('1', $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomer()->getCode());
        $this->assertSame('2012/8', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrency());
        $this->assertSame('concept', $invoice->getStatus());
        $this->assertSame('cash', $invoice->getPaymentMethod());
        $this->assertSame('', $invoice->getHeaderText());
        $this->assertSame('', $invoice->getFooterText());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame('1', $invoiceLine->getID());
        $this->assertSame('4', $invoiceLine->getArticle());
        $this->assertSame('118', $invoiceLine->getSubArticle());
        $this->assertSame('1', $invoiceLine->getQuantity());
        $this->assertSame('1', $invoiceLine->getUnits());
        $this->assertSame('true', $invoiceLine->getAllowDiscountOrPremium());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame('15.00', $invoiceLine->getValueExcl());
        $this->assertSame('0.00', $invoiceLine->getVatValue());
        $this->assertSame('15.00', $invoiceLine->getValueInc());
        $this->assertSame('15.00', $invoiceLine->getUnitsPriceExcl());
        $this->assertSame('', $invoiceLine->getFreeText1());
        $this->assertSame('', $invoiceLine->getFreeText2());
        $this->assertSame('', $invoiceLine->getFreeText3());
        $this->assertSame('8020', $invoiceLine->getDim1());
        $this->assertEquals(PerformanceType::SERVICES(), $invoiceLine->getPerformanceType());

        // TODO - Vat lines

        $this->assertSame('15.00', $invoice->getTotals()->getValueInc());
        $this->assertSame('15.00', $invoice->getTotals()->getValueExcl());
    }

    public function testSendInvoiceWorks()
    {
        $customer = new Customer();
        $customer->setCode('1000');

        $invoice = new Invoice();
        $invoice->setOffice('11024');
        $invoice->setInvoiceType('FACTUUR');
        $invoice->setInvoiceNumber('5');
        $invoice->setInvoiceDate(new \DateTimeImmutable('2012-08-31'));
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

        $this->client
            ->expects($this->once())
            ->method("sendDOMDocument")
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
}
