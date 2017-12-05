<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\TransactionApiConnector;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Secure\Login;
use PhpTwinfield\Secure\Service;

/**
 * @covers JournalTransaction
 * @covers JournalTransactionLine
 * @covers TransactionsDocument
 * @covers TransactionMapper
 * @covers TransactionApiConnector
 */
class JournalTransactionIntegrationTest extends \PHPUnit\Framework\TestCase
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
     * @var TransactionApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transactionApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->login   = $this->createMock(Login::class);
        $this->service = $this->createMock(Service::class);

        $this->login
            ->expects($this->any())
            ->method('process')
            ->willReturn(true);

        $this->transactionApiConnector = $this->createPartialMock(
            TransactionApiConnector::class,
            ['getLogin', 'createService']
        );

        $this->transactionApiConnector
            ->expects($this->any())
            ->method('createService')
            ->willReturn($this->service);

        $this->transactionApiConnector
            ->expects($this->any())
            ->method('getLogin')
            ->willReturn($this->login);
    }

    public function testGetJournalTransactionWorks()
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(realpath(__DIR__ . '/resources/journalTransactionGetResponse.xml')));
        $response = new Response($domDocument);

        $this->service
            ->expects($this->any())
            ->method('send')
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Transaction::class))
            ->willReturn($response);

        /** @var JournalTransaction[] $journalTransactions */
        $journalTransactions = $this->transactionApiConnector->get(JournalTransaction::class, 'MEMO', '201300003', '0-0-1-NL-001');
        $journalTransaction  = reset($journalTransactions);

        $this->assertInstanceOf(JournalTransaction::class, $journalTransaction);
        $this->assertEquals(Destiny::TEMPORARY(), $journalTransaction->getDestiny());
        $this->assertNull($journalTransaction->isAutoBalanceVat());
        $this->assertNull($journalTransaction->getRaiseWarning());
        $this->assertEquals(Office::fromCode('0-0-1-NL-001'), $journalTransaction->getOffice());
        $this->assertSame('MEMO', $journalTransaction->getCode());
        $this->assertSame(201300003, $journalTransaction->getNumber());
        $this->assertSame('2013/11', $journalTransaction->getPeriod());
        $this->assertSame('EUR', $journalTransaction->getCurrency());
        $this->assertSame('20131104', $journalTransaction->getDate());
        $this->assertSame('import', $journalTransaction->getOrigin());
        $this->assertNull($journalTransaction->getFreetext1());
        $this->assertNull($journalTransaction->getFreetext2());
        $this->assertNull($journalTransaction->getFreetext3());
        $this->assertNull($journalTransaction->getRegime());

        /** @var JournalTransactionLine[] $journalTransactionLines */
        $journalTransactionLines = $journalTransaction->getLines();
        $this->assertCount(2, $journalTransactionLines);

        $this->assertArrayHasKey('1', $journalTransactionLines);
        $detailLine1 = $journalTransactionLines['1'];
        $this->assertSame(JournalTransactionLine::TYPE_DETAIL, $detailLine1->getType());
        $this->assertSame('1', $detailLine1->getId());
        $this->assertSame('4008', $detailLine1->getDim1());
        $this->assertNull($detailLine1->getDim2());
        $this->assertSame(JournalTransactionLine::DEBIT, $detailLine1->getDebitCredit());
        $this->assertSame(435.55, $detailLine1->getValue());
        $this->assertSame(435.55, $detailLine1->getBaseValue());
        $this->assertSame(1.0, $detailLine1->getRate());
        $this->assertSame(653.33, $detailLine1->getRepValue());
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

        $this->assertArrayHasKey('2', $journalTransactionLines);
        $detailLine2 = $journalTransactionLines['2'];
        $this->assertSame(JournalTransactionLine::TYPE_DETAIL, $detailLine2->getType());
        $this->assertSame('2', $detailLine2->getId());
        $this->assertSame('1300', $detailLine2->getDim1());
        $this->assertSame('1000', $detailLine2->getDim2());
        $this->assertSame(JournalTransactionLine::CREDIT, $detailLine2->getDebitCredit());
        $this->assertSame(435.55, $detailLine2->getValue());
        $this->assertSame(435.55, $detailLine2->getBaseValue());
        $this->assertSame(1.0, $detailLine2->getRate());
        $this->assertSame(653.33, $detailLine2->getRepValue());
        $this->assertSame(1.500000000, $detailLine2->getRepRate());
        $this->assertSame('Invoice paid', $detailLine2->getDescription());
        $this->assertSame(JournalTransactionLine::MATCHSTATUS_AVAILABLE, $detailLine2->getMatchStatus());
        $this->assertSame(2, $detailLine2->getMatchLevel());
        $this->assertSame(435.55, $detailLine2->getBaseValueOpen());
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
            ->setDate('20131104')
            ->setOffice(Office::fromCode('001'));

        $detailLine1 = new JournalTransactionLine();
        $detailLine1
            ->setType(JournalTransactionLine::TYPE_DETAIL)
            ->setId('1')
            ->setDim1('4008')
            ->setDebitCredit(JournalTransactionLine::DEBIT)
            ->setValue(435.55);

        $detailLine2 = new JournalTransactionLine();
        $detailLine2
            ->setType(JournalTransactionLine::TYPE_DETAIL)
            ->setId('2')
            ->setDim1('1300')
            ->setDim2('1000')
            ->setDebitCredit(JournalTransactionLine::CREDIT)
            ->setValue(435.55)
            ->setInvoiceNumber('11001770')
            ->setDescription('Invoice paid');

        $journalTransaction
            ->addLine($detailLine1)
            ->addLine($detailLine2);

        $this->service
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(TransactionsDocument::class))
            ->willReturnCallback(function (TransactionsDocument $transactionsDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(realpath(__DIR__ . '/resources/journalTransactionSendRequest.xml')),
                    $transactionsDocument->saveXML()
                );

                return new Response($transactionsDocument);
            });

        $this->transactionApiConnector->send([$journalTransaction]);
    }
}
