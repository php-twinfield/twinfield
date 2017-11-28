<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\InvoiceApiConnector;
use PhpTwinfield\Customer;
use PhpTwinfield\Invoice;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\InvoiceLine;
use PhpTwinfield\InvoiceTotals;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Login;
use PhpTwinfield\Secure\Service;

/**
 * @covers Invoice
 * @covers InvoiceLine
 * @covers InvoiceTotals
 * @covers InvoicesDocument
 * @covers InvoiceMapper
 * @covers InvoiceApiConnector
 */
class InvoiceIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Login|\PHPUnit_Framework_MockObject_MockObject
     */
    private $login;

    /**
     * @var Service|\PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    /**
     * @var InvoiceApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $invoiceApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->login   = $this->createMock(Login::class);
        $this->service = $this->createMock(Service::class);

        $this->login
            ->expects($this->any())
            ->method('process')
            ->willReturn(true);

        $this->invoiceApiConnector = $this->createPartialMock(
            InvoiceApiConnector::class,
            ['getLogin', 'createService']
        );

        $this->invoiceApiConnector
            ->expects($this->any())
            ->method('createService')
            ->willReturn($this->service);

        $this->invoiceApiConnector
            ->expects($this->any())
            ->method('getLogin')
            ->willReturn($this->login);
    }

    public function testGetInvoiceWorks()
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(realpath(__DIR__ . '/resources/invoiceGetResponse.xml')));
        $response = new Response($domDocument);

        $this->service
            ->expects($this->any())
            ->method('send')
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Invoice::class))
            ->willReturn($response);

        $invoice = $this->invoiceApiConnector->get('FACTUUR', '5', '11024');

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertSame('11024', $invoice->getOffice());
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

        $this->service
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(InvoicesDocument::class))
            ->willReturnCallback(function (InvoicesDocument $invoicesDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(realpath(__DIR__ . '/resources/invoiceSendRequest.xml')),
                    $invoicesDocument->saveXML()
                );

                return new Response($invoicesDocument);
            });

        $this->invoiceApiConnector->send($invoice);
    }
}
