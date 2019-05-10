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

    protected function setUp()
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
        $this->assertSame('FACTUUR', $invoice->getInvoiceTypeToCode());
        $this->assertSame(5, $invoice->getInvoiceNumber());
        $this->assertSame('20120831', $invoice->getInvoiceDateToString());
        $this->assertSame('BNK', $invoice->getBankToCode());
        $this->assertSame(1, $invoice->getInvoiceAddressNumber());
        $this->assertSame(1, $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomerToCode());
        $this->assertSame('2012/8', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrencyToCode());
        $ReflectObject = new ReflectionClass('\PhpTwinfield\Enums\InvoiceStatus');
        $this->assertSame($ReflectObject->getConstant('CONCEPT'), $invoice->getStatus());
        $this->assertSame(\PhpTwinfield\Enums\PaymentMethod::CASH(), $invoice->getPaymentMethod());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame(1, $invoiceLine->getID());
        $this->assertSame('0', $invoiceLine->getArticleToCode());
        $this->assertSame('118', $invoiceLine->getSubArticleToSubCode());
        $this->assertSame(1, $invoiceLine->getQuantity());
        $this->assertSame(1, $invoiceLine->getUnits());
        $this->assertSame('true', $invoiceLine->getAllowDiscountOrPremiumToString());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame(15.00, $invoiceLine->getValueExclToFloat());
        $this->assertSame(0.00, $invoiceLine->getVatValueToFloat());
        $this->assertSame(15.00, $invoiceLine->getValueIncToFloat());
        $this->assertSame(15.00, $invoiceLine->getUnitsPriceExclToFloat());
        $this->assertSame('8020', $invoiceLine->getDim1ToCode());

        // TODO - Vat lines

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
        $this->assertSame('FACTUUR', $invoice->getInvoiceTypeToCode());
        $this->assertSame(5, $invoice->getInvoiceNumber());
        $this->assertSame('20120831', $invoice->getInvoiceDateToString());
        $this->assertSame('BNK', $invoice->getBankToCode());
        $this->assertSame(1, $invoice->getInvoiceAddressNumber());
        $this->assertSame(1, $invoice->getDeliverAddressNumber());
        $this->assertSame('1000', $invoice->getCustomerToCode());
        $this->assertSame('2012/8', $invoice->getPeriod());
        $this->assertSame('EUR', $invoice->getCurrencyToCode());
        $this->assertSame(\PhpTwinfield\Enums\InvoiceStatus::FINAL(), $invoice->getStatus());
        $this->assertSame(\PhpTwinfield\Enums\PaymentMethod::CASH(), $invoice->getPaymentMethod());

        $invoiceLines = $invoice->getLines();
        $this->assertCount(1, $invoiceLines);
        $this->assertArrayHasKey('1', $invoiceLines);

        /** @var InvoiceLine $invoiceLine */
        $invoiceLine = $invoiceLines['1'];

        $this->assertSame(1, $invoiceLine->getID());
        $this->assertSame('0', $invoiceLine->getArticleToCode());
        $this->assertSame('118', $invoiceLine->getSubArticleToSubCode());
        $this->assertSame(1, $invoiceLine->getQuantity());
        $this->assertSame(1, $invoiceLine->getUnits());
        $this->assertSame('true', $invoiceLine->getAllowDiscountOrPremiumToString());
        $this->assertSame('CoalesceFunctioningOnImpatienceTShirt', $invoiceLine->getDescription());
        $this->assertSame(15.00, $invoiceLine->getValueExclToFloat());
        $this->assertSame(0.00, $invoiceLine->getVatValueToFloat());
        $this->assertSame(15.00, $invoiceLine->getValueIncToFloat());
        $this->assertSame(15.00, $invoiceLine->getUnitsPriceExclToFloat());
        $this->assertSame('8020', $invoiceLine->getDim1ToCode());

        // TODO - Vat lines

        $this->assertSame(15.00, $invoice->getTotals()->getValueIncToFloat());
        $this->assertSame(15.00, $invoice->getTotals()->getValueExclToFloat());

        $this->assertSame('123456789', $invoice->getFinancialNumber());
        $this->assertSame('123456789', $invoice->getFinancialCode());
    }

    public function testSendInvoiceWorks()
    {
        $customer = new Customer();
        $customer->setCode('1000');

        $invoice = new Invoice();
        $invoice->setOffice(Office::fromCode('11024'));
        $invoice->setInvoiceTypeFromCode('FACTUUR');
        $invoice->setInvoiceNumber(5);
        $invoice->setInvoiceDateFromString('20120831');
        $invoice->setBankFromCode('BNK');
        $invoice->setInvoiceAddressNumber(1);
        $invoice->setDeliverAddressNumber(1);
        $invoice->setCustomer($customer);
        $invoice->setPeriod('2012/8');
        $invoice->setCurrencyFromCode('EUR');
        $invoice->setStatusFromString('concept');
        $invoice->setPaymentMethodFromString('cash');

        $invoiceLine = new InvoiceLine();
        $invoiceLine->setID(1);
        $invoiceLine->setArticleFromCode('4');
        $invoiceLine->setSubArticleFromSubCode('118');
        $invoiceLine->setQuantity(1);
        $invoiceLine->setUnits(1);
        $invoiceLine->setAllowDiscountOrPremiumFromString('true');
        $invoiceLine->setDescription('CoalesceFunctioningOnImpatienceTShirt');
        $invoiceLine->setValueExclFromFloat(15.00);
        $invoiceLine->setVatValueFromFloat(0.00);
        $invoiceLine->setValueIncFromFloat(15.00);
        $invoiceLine->setUnitsPriceExclFromFloat(15.00);
        $invoiceLine->setDim1FromCode('8020');
        $invoiceLine->setVatCodeFromCode('VN');
        $invoice->addLine($invoiceLine);

        $totals = new InvoiceTotals();
        $totals->setValueExclFromFloat(15.00);
        $totals->setValueIncFromFloat(15.00);
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
