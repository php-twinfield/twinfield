<?php

namespace PhpTwinfield\IntegrationTests;

use Money\Money;
use PhpTwinfield\ApiConnectors\TransactionApiConnector;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;

/**
 * @covers JournalTransaction
 * @covers JournalTransactionLine
 * @covers TransactionsDocument
 * @covers TransactionMapper
 * @covers TransactionApiConnector
 */
class JournalTransactionIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var TransactionApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transactionApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->transactionApiConnector = new TransactionApiConnector($this->connection);
    }

    public function testGetJournalTransactionWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/journalTransactionGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Transaction::class))
            ->willReturn($response);

        /** @var JournalTransaction[] $journalTransactions */
        $journalTransaction = $this->transactionApiConnector->get(JournalTransaction::class, 'MEMO', '201300003', $this->office);

        $this->assertInstanceOf(JournalTransaction::class, $journalTransaction);
        $this->assertEquals(Destiny::TEMPORARY(), $journalTransaction->getDestiny());
        $this->assertNull($journalTransaction->isAutoBalanceVat());
        $this->assertNull($journalTransaction->getRaiseWarning());
        $this->assertEquals(Office::fromCode('0-0-1-NL-001'), $journalTransaction->getOffice());
        $this->assertSame('MEMO', $journalTransaction->getCode());
        $this->assertSame(201300003, $journalTransaction->getNumber());
        $this->assertSame('2013/11', $journalTransaction->getPeriod());
        $this->assertSame('EUR', $journalTransaction->getCurrency());
        $this->assertEquals(new \DateTimeImmutable('2013-11-04'), $journalTransaction->getDate());
        $this->assertSame('import', $journalTransaction->getOrigin());
        $this->assertNull($journalTransaction->getFreetext1());
        $this->assertNull($journalTransaction->getFreetext2());
        $this->assertNull($journalTransaction->getFreetext3());
        $this->assertNull($journalTransaction->getRegime());

        /** @var JournalTransactionLine[] $journalTransactionLines */
        $journalTransactionLines = $journalTransaction->getLines();
        $this->assertCount(2, $journalTransactionLines);
        [$detailLine1, $detailLine2] = $journalTransactionLines;

        $this->assertEquals(LineType::DETAIL(), $detailLine1->getLineType());
        $this->assertSame(1, $detailLine1->getId());
        $this->assertSame('4008', $detailLine1->getDim1());
        $this->assertNull($detailLine1->getDim2());
        $this->assertEquals(DebitCredit::DEBIT(), $detailLine1->getDebitCredit());
        $this->assertEquals(Money::EUR(43555), $detailLine1->getValue());
        $this->assertEquals(Money::EUR(43555), $detailLine1->getBaseValue());
        $this->assertSame(1.0, $detailLine1->getRate());
        $this->assertEquals(Money::EUR(65333), $detailLine1->getRepValue());
        $this->assertSame(1.500000000, $detailLine1->getRepRate());
        $this->assertNull($detailLine1->getDescription());
        $this->assertSame(JournalTransactionLine::MATCHSTATUS_NOTMATCHABLE, $detailLine1->getMatchStatus());
        $this->assertNull($detailLine1->getMatchLevel());
        $this->assertNull($detailLine1->getBaseValueOpen());
        $this->assertNull($detailLine1->getVatCode());
        $this->assertNull($detailLine1->getVatValue());
        $this->assertNull($detailLine1->getPerformanceType());
        $this->assertNull($detailLine1->getPerformanceCountry());
        $this->assertNull($detailLine1->getPerformanceVatNumber());
        $this->assertNull($detailLine1->getPerformanceDate());
        $this->assertSame('', $detailLine1->getInvoiceNumber());

        $this->assertEquals(LineType::DETAIL(), $detailLine2->getLineType());
        $this->assertSame(2, $detailLine2->getId());
        $this->assertSame('1300', $detailLine2->getDim1());
        $this->assertSame('1000', $detailLine2->getDim2());
        $this->assertEquals(DebitCredit::CREDIT(), $detailLine2->getDebitCredit());
        $this->assertEquals(Money::EUR(43555), $detailLine2->getValue());
        $this->assertEquals(Money::EUR(43555), $detailLine2->getBaseValue());
        $this->assertSame(1.0, $detailLine2->getRate());
        $this->assertEquals(Money::EUR(65333), $detailLine2->getRepValue());
        $this->assertSame(1.500000000, $detailLine2->getRepRate());
        $this->assertSame('Invoice paid', $detailLine2->getDescription());
        $this->assertSame(JournalTransactionLine::MATCHSTATUS_AVAILABLE, $detailLine2->getMatchStatus());
        $this->assertSame(2, $detailLine2->getMatchLevel());
        $this->assertEquals(Money::EUR(43555), $detailLine2->getBaseValueOpen());
        $this->assertNull($detailLine2->getVatCode());
        $this->assertNull($detailLine2->getVatValue());
        $this->assertNull($detailLine2->getPerformanceType());
        $this->assertNull($detailLine2->getPerformanceCountry());
        $this->assertNull($detailLine2->getPerformanceVatNumber());
        $this->assertNull($detailLine2->getPerformanceDate());
        $this->assertSame('11001770', $detailLine2->getInvoiceNumber());
    }

    public function testSendJournalTransactionWorks()
    {
        $journalTransaction = new JournalTransaction();
        $journalTransaction
            ->setDestiny(Destiny::TEMPORARY())
            ->setCode('MEMO')
            ->setCurrency('EUR')
            ->setDate(new \DateTimeImmutable('2013-11-04'))
            ->setOffice(Office::fromCode('001'));

        $detailLine1 = new JournalTransactionLine();
        $detailLine1
            ->setLineType(LineType::DETAIL())
            ->setId('1')
            ->setDim1('4008')
            ->setValue(Money::EUR(-43555));

        $detailLine2 = new JournalTransactionLine();
        $detailLine2
            ->setLineType(LineType::DETAIL())
            ->setId('2')
            ->setDim1('1300')
            ->setDim2('1000')
            ->setValue(Money::EUR(43555))
            ->setInvoiceNumber('11001770')
            ->setDescription('Invoice paid');

        $journalTransaction
            ->addLine($detailLine1)
            ->addLine($detailLine2);

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(TransactionsDocument::class))
            ->willReturnCallback(function (TransactionsDocument $transactionsDocument): Response {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(__DIR__ . '/resources/journalTransactionSendRequest.xml'),
                    $transactionsDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->transactionApiConnector->send($journalTransaction);
    }

    protected function getSuccessfulResponse(): Response
    {
        return Response::fromString(
    '<transactions result="1"><transaction location="temporary">
                <header>
                    <code name="Verkoopfactuur" shortname="Verkoop">VRK</code>
                    <date>20170901</date>
                    <period>2017/09</period>
                    <office name="Development BV" shortname="Development BV">DEV1000</office>
                    <number>201702412</number>
                </header>
            </transaction>
        </transactions>'
        );
    }
}
