<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\TransactionApiConnector;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Response\Response;
use PhpTwinfield\PurchaseTransaction;
use PhpTwinfield\PurchaseTransactionLine;
use PhpTwinfield\Secure\Login;
use PhpTwinfield\Secure\Service;

/**
 * @covers PurchaseTransaction
 * @covers PurchaseTransactionLine
 * @covers TransactionsDocument
 * @covers TransactionMapper
 * @covers TransactionApiConnector
 */
class PurchaseTransactionIntegrationTest extends \PHPUnit\Framework\TestCase
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

    public function testGetPurchaseTransactionWorks()
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(realpath(__DIR__ . '/resources/purchaseTransactionGetResponse.xml')));
        $response = new Response($domDocument);

        $this->service
            ->expects($this->any())
            ->method('send')
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Transaction::class))
            ->willReturn($response);

        /** @var PurchaseTransaction[] $purchaseTransactions */
        $purchaseTransactions = $this->transactionApiConnector->get(PurchaseTransaction::class, 'INK', '201300021', '001');
        $purchaseTransaction  = reset($purchaseTransactions);

        $this->assertInstanceOf(PurchaseTransaction::class, $purchaseTransaction);
        $this->assertSame(PurchaseTransaction::DESTINY_TEMPORARY, $purchaseTransaction->getDestiny());
        $this->assertNull($purchaseTransaction->getAutoBalanceVat());
        $this->assertSame(false, $purchaseTransaction->getRaiseWarning());
        $this->assertSame('001', $purchaseTransaction->getOffice());
        $this->assertSame('INK', $purchaseTransaction->getCode());
        $this->assertSame(201300021, $purchaseTransaction->getNumber());
        $this->assertSame('2013/05', $purchaseTransaction->getPeriod());
        $this->assertSame('EUR', $purchaseTransaction->getCurrency());
        $this->assertSame('20130502', $purchaseTransaction->getDate());
        $this->assertSame('import', $purchaseTransaction->getOrigin());
        $this->assertNull($purchaseTransaction->getFreetext1());
        $this->assertNull($purchaseTransaction->getFreetext2());
        $this->assertNull($purchaseTransaction->getFreetext3());
        $this->assertSame('20130506', $purchaseTransaction->getDueDate());
        $this->assertSame('20130-5481', $purchaseTransaction->getInvoiceNumber());
        $this->assertSame('+++100/0160/01495+++', $purchaseTransaction->getPaymentReference());

        /** @var PurchaseTransactionLine[] $purchaseTransactionLines */
        $purchaseTransactionLines = $purchaseTransaction->getLines();
        $this->assertCount(3, $purchaseTransactionLines);

        $this->assertArrayHasKey('1', $purchaseTransactionLines);
        $totalLine = $purchaseTransactionLines['1'];
        $this->assertSame(PurchaseTransactionLine::TYPE_TOTAL, $totalLine->getType());
        $this->assertSame('1', $totalLine->getId());
        $this->assertSame('1600', $totalLine->getDim1());
        $this->assertSame('2000', $totalLine->getDim2());
        $this->assertSame(PurchaseTransactionLine::CREDIT, $totalLine->getDebitCredit());
        $this->assertSame(121.00, $totalLine->getValue());
        $this->assertSame(121.00, $totalLine->getBaseValue());
        $this->assertSame(1.0, $totalLine->getRate());
        $this->assertSame(156.53, $totalLine->getRepValue());
        $this->assertSame(1.293600000, $totalLine->getRepRate());
        $this->assertSame('', $totalLine->getDescription());
        $this->assertSame(PurchaseTransactionLine::MATCHSTATUS_AVAILABLE, $totalLine->getMatchStatus());
        $this->assertSame(2, $totalLine->getMatchLevel());
        $this->assertSame(121.00, $totalLine->getBaseValueOpen());
        $this->assertNull($totalLine->getVatCode());
        $this->assertNull($totalLine->getVatValue());
        $this->assertSame(21.00, $totalLine->getVatTotal());
        $this->assertSame(21.00, $totalLine->getVatBaseTotal());
        $this->assertSame(121.00, $totalLine->getValueOpen());

        $this->assertArrayHasKey('2', $purchaseTransactionLines);
        $detailLine = $purchaseTransactionLines['2'];
        $this->assertSame(PurchaseTransactionLine::TYPE_DETAIL, $detailLine->getType());
        $this->assertSame('2', $detailLine->getId());
        $this->assertSame('8020', $detailLine->getDim1());
        $this->assertNull($detailLine->getDim2());
        $this->assertSame(PurchaseTransactionLine::DEBIT, $detailLine->getDebitCredit());
        $this->assertSame(100.00, $detailLine->getValue());
        $this->assertSame(100.00, $detailLine->getBaseValue());
        $this->assertSame(1.0, $detailLine->getRate());
        $this->assertSame(129.36, $detailLine->getRepValue());
        $this->assertSame(1.293600000, $detailLine->getRepRate());
        $this->assertSame('Outfit', $detailLine->getDescription());
        $this->assertSame(PurchaseTransactionLine::MATCHSTATUS_NOTMATCHABLE, $detailLine->getMatchStatus());
        $this->assertNull($detailLine->getMatchLevel());
        $this->assertNull($detailLine->getBaseValueOpen());
        $this->assertSame('IH', $detailLine->getVatCode());
        $this->assertSame(21.00, $detailLine->getVatValue());
        $this->assertNull($detailLine->getVatTotal());
        $this->assertNull($detailLine->getVatBaseTotal());
        $this->assertNull($detailLine->getValueOpen());

        $this->assertArrayHasKey('3', $purchaseTransactionLines);
        $vatLine = $purchaseTransactionLines['3'];
        $this->assertSame(PurchaseTransactionLine::TYPE_VAT, $vatLine->getType());
        $this->assertSame('3', $vatLine->getId());
        $this->assertSame('1510', $vatLine->getDim1());
        $this->assertNull($vatLine->getDim2());
        $this->assertSame(PurchaseTransactionLine::DEBIT, $vatLine->getDebitCredit());
        $this->assertSame(21.00, $vatLine->getValue());
        $this->assertSame(21.00, $vatLine->getBaseValue());
        $this->assertSame(1.0, $vatLine->getRate());
        $this->assertSame(27.17, $vatLine->getRepValue());
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
            ->setDestiny(PurchaseTransaction::DESTINY_TEMPORARY)
            ->setRaiseWarning(false)
            ->setCode('INK')
            ->setCurrency('EUR')
            ->setDate('20130502')
            ->setPeriod('2013/05')
            ->setInvoiceNumber('20130-5481')
            ->setPaymentReference('+++100/0160/01495+++')
            ->setOffice('001')
            ->setDueDate('20130506');

        $totalLine = new PurchaseTransactionLine();
        $totalLine
            ->setType(PurchaseTransactionLine::TYPE_TOTAL)
            ->setId('1')
            ->setDim1('1600')
            ->setDim2('2000')
            ->setValue(121.00)
            ->setDebitCredit(PurchaseTransactionLine::CREDIT)
            ->setDescription('');

        $detailLine = new PurchaseTransactionLine();
        $detailLine
            ->setType(PurchaseTransactionLine::TYPE_DETAIL)
            ->setId('2')
            ->setDim1('8020')
            ->setValue(100.00)
            ->setDebitCredit(PurchaseTransactionLine::DEBIT)
            ->setDescription('Outfit')
            ->setVatCode('IH');

        $purchaseTransaction
            ->addLine($totalLine)
            ->addLine($detailLine);

        $this->service
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(TransactionsDocument::class))
            ->willReturnCallback(function (TransactionsDocument $transactionsDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(realpath(__DIR__ . '/resources/purchaseTransactionSendRequest.xml')),
                    $transactionsDocument->saveXML()
                );

                return new Response($transactionsDocument);
            });

        $this->transactionApiConnector->send([$purchaseTransaction]);
    }
}
