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
use PhpTwinfield\PurchaseTransaction;
use PhpTwinfield\PurchaseTransactionLine;
use PhpTwinfield\Secure\Connection;

/**
 * @covers PurchaseTransaction
 * @covers PurchaseTransactionLine
 * @covers TransactionsDocument
 * @covers TransactionMapper
 * @covers TransactionApiConnector
 */
class PurchaseTransactionIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var TransactionApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transactionApiConnector;

    protected function setUp()
    {
        parent::setUp();
        $this->transactionApiConnector = new TransactionApiConnector($this->login);
    }

    public function testGetPurchaseTransactionWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/purchaseTransactionGetResponse.xml'));

        $this->client
            ->expects($this->once())
            ->method("sendDOMDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Transaction::class))
            ->willReturn($response);

        /** @var PurchaseTransaction $purchaseTransaction */
        $purchaseTransaction = $this->transactionApiConnector->get(PurchaseTransaction::class, 'INK', '201300021', $this->office);

        $this->assertInstanceOf(PurchaseTransaction::class, $purchaseTransaction);
        $this->assertEquals(Destiny::TEMPORARY(), $purchaseTransaction->getDestiny());
        $this->assertNull($purchaseTransaction->isAutoBalanceVat());
        $this->assertSame(false, $purchaseTransaction->getRaiseWarning());
        $this->assertEquals(Office::fromCode('001'), $purchaseTransaction->getOffice());
        $this->assertSame('INK', $purchaseTransaction->getCode());
        $this->assertSame(201300021, $purchaseTransaction->getNumber());
        $this->assertSame('2013/05', $purchaseTransaction->getPeriod());
        $this->assertSame('EUR', $purchaseTransaction->getCurrency());
        $this->assertEquals(new \DateTimeImmutable('2013-05-02'), $purchaseTransaction->getDate());
        $this->assertSame('import', $purchaseTransaction->getOrigin());
        $this->assertNull($purchaseTransaction->getFreetext1());
        $this->assertNull($purchaseTransaction->getFreetext2());
        $this->assertNull($purchaseTransaction->getFreetext3());
        $this->assertEquals(new \DateTimeImmutable('2013-05-06'), $purchaseTransaction->getDueDate());
        $this->assertSame('20130-5481', $purchaseTransaction->getInvoiceNumber());
        $this->assertSame('+++100/0160/01495+++', $purchaseTransaction->getPaymentReference());

        /** @var PurchaseTransactionLine[] $purchaseTransactionLines */
        $purchaseTransactionLines = $purchaseTransaction->getLines();
        $this->assertCount(3, $purchaseTransactionLines);
        [$totalLine, $detailLine, $vatLine] = $purchaseTransactionLines;

        $this->assertEquals(LineType::TOTAL(), $totalLine->getLineType());
        $this->assertSame(1, $totalLine->getId());
        $this->assertSame('1600', $totalLine->getDim1());
        $this->assertSame('2000', $totalLine->getDim2());
        $this->assertEquals(DebitCredit::CREDIT(), $totalLine->getDebitCredit());
        $this->assertEquals(Money::EUR(12100), $totalLine->getValue());
        $this->assertEquals(Money::EUR(12100), $totalLine->getBaseValue());
        $this->assertSame(1.0, $totalLine->getRate());
        $this->assertEquals(Money::EUR(15653), $totalLine->getRepValue());
        $this->assertSame(1.293600000, $totalLine->getRepRate());
        $this->assertSame('', $totalLine->getDescription());
        $this->assertSame(PurchaseTransactionLine::MATCHSTATUS_AVAILABLE, $totalLine->getMatchStatus());
        $this->assertSame(2, $totalLine->getMatchLevel());
        $this->assertEquals(Money::EUR(12100), $totalLine->getBaseValueOpen());
        $this->assertNull($totalLine->getVatCode());
        $this->assertNull($totalLine->getVatValue());
        $this->assertEquals(Money::EUR(2100), $totalLine->getVatTotal());
        $this->assertEquals(Money::EUR(2100), $totalLine->getVatBaseTotal());
        $this->assertEquals(Money::EUR(12100), $totalLine->getValueOpen());

        $this->assertEquals(LineType::DETAIL(), $detailLine->getLineType());
        $this->assertSame(2, $detailLine->getId());
        $this->assertSame('8020', $detailLine->getDim1());
        $this->assertNull($detailLine->getDim2());
        $this->assertEquals(DebitCredit::DEBIT(), $detailLine->getDebitCredit());
        $this->assertEquals(Money::EUR(10000), $detailLine->getValue());
        $this->assertEquals(Money::EUR(10000), $detailLine->getBaseValue());
        $this->assertSame(1.0, $detailLine->getRate());
        $this->assertEquals(Money::EUR(12936), $detailLine->getRepValue());
        $this->assertSame(1.293600000, $detailLine->getRepRate());
        $this->assertSame('Outfit', $detailLine->getDescription());
        $this->assertSame(PurchaseTransactionLine::MATCHSTATUS_NOTMATCHABLE, $detailLine->getMatchStatus());
        $this->assertNull($detailLine->getMatchLevel());
        $this->assertNull($detailLine->getBaseValueOpen());
        $this->assertSame('IH', $detailLine->getVatCode());
        $this->assertEquals(Money::EUR(2100), $detailLine->getVatValue());
        $this->assertNull($detailLine->getVatTotal());
        $this->assertNull($detailLine->getVatBaseTotal());
        $this->assertNull($detailLine->getValueOpen());

        $this->assertEquals(LineType::VAT(), $vatLine->getLineType());
        $this->assertSame(3, $vatLine->getId());
        $this->assertSame('1510', $vatLine->getDim1());
        $this->assertNull($vatLine->getDim2());
        $this->assertEquals(DebitCredit::DEBIT(), $vatLine->getDebitCredit());
        $this->assertEquals(Money::EUR(2100), $vatLine->getValue());
        $this->assertEquals(Money::EUR(2100), $vatLine->getBaseValue());
        $this->assertSame(1.0, $vatLine->getRate());
        $this->assertEquals(Money::EUR(2717), $vatLine->getRepValue());
        $this->assertSame(1.293600000, $vatLine->getRepRate());
        $this->assertNull($vatLine->getDescription());
        $this->assertNull($vatLine->getMatchStatus());
        $this->assertNull($vatLine->getMatchLevel());
        $this->assertNull($vatLine->getBaseValueOpen());
        $this->assertSame('IH', $vatLine->getVatCode());
        $this->assertNull($vatLine->getVatValue());
        $this->assertNull($vatLine->getVatTotal());
        $this->assertNull($vatLine->getVatBaseTotal());
        $this->assertNull($vatLine->getValueOpen());
    }

    public function testSendPurchaseTransactionWorks()
    {
        $purchaseTransaction = new PurchaseTransaction();
        $purchaseTransaction
            ->setDestiny(Destiny::TEMPORARY())
            ->setRaiseWarning(false)
            ->setCode('INK')
            ->setCurrency('EUR')
            ->setDate(new \DateTimeImmutable('2013-05-02'))
            ->setPeriod('2013/05')
            ->setInvoiceNumber('20130-5481')
            ->setPaymentReference('+++100/0160/01495+++')
            ->setOffice(Office::fromCode('001'))
            ->setDueDate(new \DateTimeImmutable('2013-05-06'));

        $totalLine = new PurchaseTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setId('1')
            ->setDim1('1600')
            ->setDim2('2000')
            ->setValue(Money::EUR(12100))
            ->setDescription('');

        $detailLine = new PurchaseTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId('2')
            ->setDim1('8020')
            ->setValue(Money::EUR(-10000))
            ->setDescription('Outfit')
            ->setVatCode('IH');

        $purchaseTransaction
            ->addLine($totalLine)
            ->addLine($detailLine);

        $this->client
            ->expects($this->once())
            ->method("sendDOMDocument")
            ->with($this->isInstanceOf(TransactionsDocument::class))
            ->willReturnCallback(function (TransactionsDocument $transactionsDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(__DIR__ . '/resources/purchaseTransactionSendRequest.xml'),
                    $transactionsDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->transactionApiConnector->send($purchaseTransaction);
    }
}
