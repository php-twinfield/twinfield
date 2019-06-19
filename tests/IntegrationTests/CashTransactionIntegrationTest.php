<?php

namespace PhpTwinfield\IntegrationTests;

use DateTimeImmutable;
use Money\Money;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\ApiConnectors\TransactionApiConnector;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\CashTransactionLine;
use PhpTwinfield\Currency;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchStatus;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;

/**
 * @covers SalesTransaction
 * @covers SalesTransactionLine
 * @covers TransactionsDocument
 * @covers TransactionMapper
 * @covers TransactionApiConnector
 */
class CashTransactionIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var TransactionApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transactionApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->transactionApiConnector = new TransactionApiConnector($this->connection);
        
        $mockOfficeApiConnector = \Mockery::mock('overload:'.OfficeApiConnector::class)->makePartial();
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

    public function testGetCashTransactionWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/cashTransactionGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Transaction::class))
            ->willReturn($response);

        /** @var CashTransaction $cashTransaction */
        $cashTransaction = $this->transactionApiConnector->get(CashTransaction::class, 'CASH', '201300008', $this->office);

        $this->assertInstanceOf(CashTransaction::class, $cashTransaction);
        $this->assertEquals(Destiny::TEMPORARY(), $cashTransaction->getDestiny());
        $this->assertNull($cashTransaction->getAutoBalanceVat());
        $this->assertSame(false, $cashTransaction->getRaiseWarning());
        $this->assertEquals(Office::fromCode('001'), $cashTransaction->getOffice());
        $this->assertSame('CASH', $cashTransaction->getCode());
        $this->assertSame(201300008, $cashTransaction->getNumber());
        $this->assertSame('2013/11', $cashTransaction->getPeriod());
        $this->assertEquals('EUR', $cashTransaction->getCurrencyToString());
        $this->assertEquals(new DateTimeImmutable('2013-11-04'), $cashTransaction->getDate());
        $this->assertSame('import', $cashTransaction->getOrigin());
        $this->assertNull($cashTransaction->getFreetext1());
        $this->assertNull($cashTransaction->getFreetext2());
        $this->assertNull($cashTransaction->getFreetext3());
        $this->assertSame(4, $cashTransaction->getStatementNumber());
        $this->assertTrue(Money::EUR(97401)->equals($cashTransaction->getStartValue()));
        $this->assertTrue(Money::EUR(140956)->equals($cashTransaction->getCloseValue()));

        /** @var CashTransactionLine[] $cashTransactionLines */
        $cashTransactionLines = $cashTransaction->getLines();
        $this->assertCount(2, $cashTransactionLines);
        [$totalLine, $detailLine] = $cashTransactionLines;

        $this->assertEquals(LineType::TOTAL(), $totalLine->getLineType());
        $this->assertSame(1, $totalLine->getId());
        $this->assertSame('1002', $totalLine->getDim1ToString());
        $this->assertEquals(DebitCredit::DEBIT(), $totalLine->getDebitCredit());
        $this->assertEquals(Money::EUR(43555), $totalLine->getValue());
        $this->assertEquals(Money::EUR(43555), $totalLine->getBaseValue());
        $this->assertSame(1.0, $totalLine->getRate());
        $this->assertEquals(Money::USD(65333), $totalLine->getRepValue());
        $this->assertSame(1.500000000, $totalLine->getRepRate());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\MatchStatus');
        $this->assertSame($ReflectObject->getConstant('NOTMATCHABLE'), (string)$totalLine->getMatchStatus());
        $this->assertNull($totalLine->getMatchLevel());
        $this->assertNull($totalLine->getBaseValueOpen());
        $this->assertNull($totalLine->getVatCode());
        $this->assertNull($totalLine->getVatValue());
        $this->assertNull($totalLine->getVatTotal());
        $this->assertNull($totalLine->getVatBaseTotal());
        $this->assertNull($totalLine->getPerformanceType());
        $this->assertNull($totalLine->getPerformanceCountry());
        $this->assertNull($totalLine->getPerformanceVatNumber());
        $this->assertNull($totalLine->getPerformanceDate());

        $this->assertEquals(LineType::DETAIL(), $detailLine->getLineType());
        $this->assertSame(2, $detailLine->getId());
        $this->assertSame('1300', $detailLine->getDim1ToString());
        $this->assertSame('1000', $detailLine->getDim2ToString());
        $this->assertEquals(DebitCredit::CREDIT(), $detailLine->getDebitCredit());
        $this->assertEquals(Money::EUR(43555), $detailLine->getValue());
        $this->assertEquals(Money::EUR(43555), $totalLine->getBaseValue());
        $this->assertSame(1.0, $totalLine->getRate());
        $this->assertEquals(Money::USD(65333), $totalLine->getRepValue());
        $this->assertSame(1.500000000, $totalLine->getRepRate());
        $this->assertSame('Invoice paid', $detailLine->getDescription());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\MatchStatus');
        $this->assertSame($ReflectObject->getConstant('AVAILABLE'), (string)$detailLine->getMatchStatus());
        $this->assertSame(2, $detailLine->getMatchLevel());
        $this->assertEquals(Money::EUR(43555), $detailLine->getBaseValueOpen());
        $this->assertEquals(Money::USD(65333), $detailLine->getRepValue());
        $this->assertNull($detailLine->getVatCodeToString());
        $this->assertNull($detailLine->getVatValue());
        $this->assertNull($detailLine->getVatTotal());
        $this->assertNull($detailLine->getVatBaseTotal());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\PerformanceType');
        $this->assertSame($ReflectObject->getConstant('EMPTY'), (string)$detailLine->getPerformanceType());
        $this->assertNull($detailLine->getPerformanceCountryToString());
        $this->assertNull($detailLine->getPerformanceVatNumber());
        $this->assertNull($detailLine->getPerformanceDate());
    }

    public function testSendCashTransactionWorks()
    {
        $cashTransaction = new CashTransaction();
        $cashTransaction
            ->setOffice(Office::fromCode('001'))
            ->setDestiny(Destiny::TEMPORARY())
            ->setRaiseWarning(false)
            ->setCode('CASH')
            ->setCurrencyFromString('EUR')
            ->setDate(new DateTimeImmutable('2013-11-04'))
            ->setStatementNumber(4)
            ->setStartValue(Money::EUR(97401));

        $totalLine = new CashTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setId('1')
            ->setDim1FromString('1002')
            ->setValue(Money::EUR(43555));

        $detailLine = new CashTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId('2')
            ->setDim1FromString('1300')
            ->setDim2FromString('1000')
            ->setValue(Money::EUR(43555))
            ->setDescription('Invoice paid');

        $cashTransaction
            ->addLine($totalLine)
            ->addLine($detailLine);

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(TransactionsDocument::class))
            ->willReturnCallback(function (TransactionsDocument $transactionsDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(realpath(__DIR__ . '/resources/cashTransactionSendRequest.xml')),
                    $transactionsDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->transactionApiConnector->send($cashTransaction);
    }

    protected function getSuccessfulResponse(): Response
    {
        return Response::fromString(
            '<transactions result="1"><transaction location="temporary">
                <header>
                    <office name="001" shortname="001">001</office>
                    <code name="Cash" shortname="Cash">CASH</code>
                    <currency name="Euro" shortname="Euro">EUR</currency>
                    <date>20131104</date>
                    <statementnumber>4</statementnumber>
                    <startvalue>974.01</startvalue>
                    <closevalue>1409.56</closevalue>
                    <origin>import</origin>
                    <user name="Marcel" shortname="Marcel">MARCEL</user>
                    <period>2013/11</period>
                    <number>201300008</number>
                </header>
            </transaction>
        </transactions>'
        );
    }
}
