<?php

namespace PhpTwinfield\IntegrationTests;

use Money\Money;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\ApiConnectors\TransactionApiConnector;
use PhpTwinfield\Currency;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchStatus;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\SalesTransactionLine;

/**
 * @covers SalesTransaction
 * @covers SalesTransactionLine
 * @covers TransactionsDocument
 * @covers TransactionMapper
 * @covers TransactionApiConnector
 */
class SalesTransactionIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var TransactionApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transactionApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->transactionApiConnector = new TransactionApiConnector($this->connection);
        
        $mockOfficeApiConnector = \Mockery::mock('overload:'.OfficeApiConnector::class);
        $mockOfficeApiConnector->shouldReceive('get')->andReturnUsing(function() {
            $baseCurrency = new Currency;
            $baseCurrency->setCode('EUR');
            $reportingCurrency = new Currency;
            $reportingCurrency->setCode('USD');
            
            $office = new Office;
            $office->setResult(1);
            $office->setBaseCurrency($baseCurrency);
            $office->setReportingCurrency($reportingCurrency);
            return $office;
        });
    }

    public function testGetSalesTransactionWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/salesTransactionGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Transaction::class))
            ->willReturn($response);

        $salesTransaction = $this->transactionApiConnector->get(SalesTransaction::class, 'SLS', '201300095', $this->office);

        $this->assertInstanceOf(SalesTransaction::class, $salesTransaction);
        $this->assertEquals(Destiny::TEMPORARY(), $salesTransaction->getDestiny());
        $this->assertNull($salesTransaction->getAutoBalanceVat());
        $this->assertSame(false, $salesTransaction->getRaiseWarning());
        $this->assertEquals(Office::fromCode('001'), $salesTransaction->getOffice());
        $this->assertSame('SLS', $salesTransaction->getCode());
        $this->assertSame(201300095, $salesTransaction->getNumber());
        $this->assertSame('2013/05', $salesTransaction->getPeriod());
        $this->assertEquals('EUR', $salesTransaction->getCurrencyToString());
        $this->assertEquals(new \DateTimeImmutable('2013-05-02'), $salesTransaction->getDate());
        $this->assertSame('import', $salesTransaction->getOrigin());
        $this->assertNull($salesTransaction->getFreetext1());
        $this->assertNull($salesTransaction->getFreetext2());
        $this->assertNull($salesTransaction->getFreetext3());
        $this->assertEquals(new \DateTimeImmutable('2013-05-06'), $salesTransaction->getDueDate());
        $this->assertSame('20130-6000', $salesTransaction->getInvoiceNumber());
        $this->assertSame('+++100/0160/01495+++', $salesTransaction->getPaymentReference());
        $this->assertNull($salesTransaction->getOriginReference());

        /** @var SalesTransactionLine[] $salesTransactionLines */
        $salesTransactionLines = $salesTransaction->getLines();
        $this->assertCount(3, $salesTransactionLines);
        [$totalLine, $detailLine, $vatLine] = $salesTransactionLines;

        $this->assertEquals(LineType::TOTAL(), $totalLine->getLineType());
        $this->assertSame(1, $totalLine->getId());
        $this->assertSame('1300', $totalLine->getDim1ToString());
        $this->assertSame('1000', $totalLine->getDim2ToString());
        $this->assertEquals(DebitCredit::DEBIT(), $totalLine->getDebitCredit());
        $this->assertEquals(Money::EUR(12100), $totalLine->getValue());
        $this->assertEquals(Money::EUR(12100), $totalLine->getBaseValue());
        $this->assertSame(1.0, $totalLine->getRate());
        $this->assertEquals(Money::USD(15653), $totalLine->getRepValue());
        $this->assertSame(1.293600000, $totalLine->getRepRate());
        $this->assertNull($totalLine->getDescription());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\MatchStatus');
        $this->assertSame($ReflectObject->getConstant('AVAILABLE'), (string)$totalLine->getMatchStatus());
        $this->assertSame(2, $totalLine->getMatchLevel());
        $this->assertEquals(Money::EUR(12100), $totalLine->getBaseValueOpen());
        $this->assertNull($totalLine->getVatCode());
        $this->assertNull($totalLine->getVatValue());
        $this->assertEquals(Money::EUR(2100), $totalLine->getVatTotal());
        $this->assertEquals(Money::EUR(2100), $totalLine->getVatBaseTotal());
        $this->assertEquals(Money::EUR(12100), $totalLine->getValueOpen());
        $this->assertNull($totalLine->getPerformanceType());
        $this->assertNull($totalLine->getPerformanceCountry());
        $this->assertNull($totalLine->getPerformanceVatNumber());
        $this->assertNull($totalLine->getPerformanceDate());

        $this->assertEquals(LineType::DETAIL(), $detailLine->getLineType());
        $this->assertSame(2, $detailLine->getId());
        $this->assertSame('8020', $detailLine->getDim1ToString());
        $this->assertNull($detailLine->getDim2ToString());
        $this->assertEquals(DebitCredit::CREDIT(), $detailLine->getDebitCredit());
        $this->assertEquals(Money::EUR(10000), $detailLine->getValue());
        $this->assertEquals(Money::EUR(10000), $detailLine->getBaseValue());
        $this->assertSame(1.0, $detailLine->getRate());
        $this->assertEquals(Money::USD(12936), $detailLine->getRepValue());
        $this->assertSame(1.293600000, $detailLine->getRepRate());
        $this->assertSame('Outfit', $detailLine->getDescription());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\MatchStatus');
        $this->assertSame($ReflectObject->getConstant('NOTMATCHABLE'), (string)$detailLine->getMatchStatus());
        $this->assertNull($detailLine->getMatchLevel());
        $this->assertNull($detailLine->getBaseValueOpen());
        $this->assertSame('VH', $detailLine->getVatCodeToString());
        $this->assertEquals(Money::EUR(2100), $detailLine->getVatValue());
        $this->assertNull($detailLine->getVatTotal());
        $this->assertNull($detailLine->getVatBaseTotal());
        $this->assertNull($detailLine->getValueOpen());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PerformanceType');
        $this->assertSame($ReflectObject->getConstant('EMPTY'), (string)$detailLine->getPerformanceType());
        $this->assertNull($detailLine->getPerformanceCountryToString());
        $this->assertNull($detailLine->getPerformanceVatNumber());
        $this->assertNull($detailLine->getPerformanceDate());

        $this->assertEquals(LineType::VAT(), $vatLine->getLineType());
        $this->assertSame(3, $vatLine->getId());
        $this->assertSame('1530', $vatLine->getDim1ToString());
        $this->assertNull($vatLine->getDim2());
        $this->assertEquals(DebitCredit::CREDIT(), $vatLine->getDebitCredit());
        $this->assertEquals(Money::EUR(2100), $vatLine->getValue());
        $this->assertEquals(Money::EUR(2100), $vatLine->getBaseValue());
        $this->assertSame(1.0, $vatLine->getRate());
        $this->assertEquals(Money::USD(2717), $vatLine->getRepValue());
        $this->assertSame(1.293600000, $vatLine->getRepRate());
        $this->assertNull($vatLine->getDescription());
        $this->assertNull($vatLine->getMatchStatus());
        $this->assertNull($vatLine->getMatchLevel());
        $this->assertNull($vatLine->getBaseValueOpen());
        $this->assertSame('VH', $vatLine->getVatCodeToString());
        $this->assertNull($vatLine->getVatValue());
        $this->assertNull($vatLine->getVatTotal());
        $this->assertNull($vatLine->getVatBaseTotal());
        $this->assertNull($vatLine->getValueOpen());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PerformanceType');
        $this->assertSame($ReflectObject->getConstant('EMPTY'), (string)$vatLine->getPerformanceType());
        $this->assertNull($vatLine->getPerformanceCountryToString());
        $this->assertNull($vatLine->getPerformanceVatNumber());
        $this->assertNull($vatLine->getPerformanceDate());
    }

    public function testSendSalesTransactionWorks()
    {
        $salesTransaction = new SalesTransaction();
        $salesTransaction
            ->setDestiny(Destiny::TEMPORARY())
            ->setRaiseWarning(false)
            ->setCode('SLS')
            ->setCurrencyFromString('EUR')
            ->setDate(new \DateTimeImmutable('2013-05-02'))
            ->setPeriod('2013/05')
            ->setInvoiceNumber('20130-6000')
            ->setPaymentReference('+++100/0160/01495+++')
            ->setOffice(Office::fromCode('001'))
            ->setDueDate(new \DateTimeImmutable('2013-05-06'));

        $totalLine = new SalesTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setId('1')
            ->setDim1FromString('1300')
            ->setDim2FromString('1000')
            ->setValue(Money::EUR(12100))
            ->setDescription('');

        $detailLine = new SalesTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId('2')
            ->setDim1FromString('8020')
            ->setValue(Money::EUR(10000))
            ->setDescription('Outfit')
            ->setVatCodeFromString('VH');

        $salesTransaction
            ->addLine($totalLine)
            ->addLine($detailLine);

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(TransactionsDocument::class))
            ->willReturnCallback(function (TransactionsDocument $transactionsDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(realpath(__DIR__ . '/resources/salesTransactionSendRequest.xml')),
                    $transactionsDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->transactionApiConnector->send($salesTransaction);
    }

    protected function getSuccessfulResponse(): Response
    {
        return Response::fromString(
            '<transactions result="1"><transaction location="temporary">
                <header>
                    <code name="Verkoopfactuur" shortname="Verkoop">VRK</code>
                    <date>20170901</date>
                    <duedate>20170901</duedate>
                    <period>2017/09</period>
                    <office name="Development BV" shortname="Development BV">DEV1000</office>
                    <number>201702412</number>
                </header>
            </transaction>
        </transactions>'
        );
    }
}
