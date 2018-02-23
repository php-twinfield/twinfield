<?php

namespace PhpTwinfield\UnitTests;

use Money\Currency;
use Money\Money;
use PhpTwinfield\ApiConnectors\BankTransactionApiConnector;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\BookingReference;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Exception;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;
use PhpTwinfield\Transactions\BankTransactionLine\Vat;
use PHPUnit\Framework\TestCase;

class BankTransactionApiConnectorTest extends TestCase
{
    /**
     * @var BankTransactionApiConnector
     */
    protected $apiConnector;

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processXmlService;

    /**
     * @var Office
     */
    protected $office;

    protected function setUp()
    {
        parent::setUp();

        $this->processXmlService = $this->getMockBuilder(ProcessXmlService::class)
            ->setMethods(["sendDocument"])
            ->disableOriginalConstructor()
            ->getMock();

        /** @var AuthenticatedConnection|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->createMock(AuthenticatedConnection::class);
        $connection
            ->expects($this->any())
            ->method("getAuthenticatedClient")
            ->willReturn($this->processXmlService);

        $this->apiConnector = new BankTransactionApiConnector($connection);
        $this->office = Office::fromCode("XXX101");
    }

    private function createBankTransaction(): BankTransaction
    {
        $banktransaction = new BankTransaction();
        $banktransaction->setDestiny(Destiny::TEMPORARY());
        $banktransaction->setOffice($this->office);

        return $banktransaction;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/2-failed-and-1-successful-banktransactions.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $responses = $this->apiConnector->sendAll([
            $this->createBankTransaction(),
            $this->createBankTransaction(),
            $this->createBankTransaction(),
        ]);

        $this->assertCount(3, $responses);

        [$response1, $response2, $response3] = $responses;

        try {
            $response1->unwrap();
        } catch (Exception $e) {
            $this->assertEquals("De boeking is niet in balans. Er ontbreekt 0.01 debet.//De boeking balanceert niet in de basisvaluta. Er ontbreekt 0.01 debet.//De boeking balanceert niet in de rapportagevaluta. Er ontbreekt 0.01 debet.", $e->getMessage());
        }

        try {
            $response2->unwrap();
        } catch (Exception $e) {
            $this->assertEquals("De boeking is niet in balans. Er ontbreekt 0.01 debet.//De boeking balanceert niet in de basisvaluta. Er ontbreekt 0.01 debet.//De boeking balanceert niet in de rapportagevaluta. Er ontbreekt 0.01 debet.", $e->getMessage());
        }

        /** @var BankTransaction $banktransaction3 */
        $banktransaction3 = $response3->unwrap();

        $this->assertEquals("BNK", $banktransaction3->getCode());
        $this->assertEquals("OFFICE001", $banktransaction3->getOffice()->getCode());
        $this->assertEquals("2017/08", $banktransaction3->getPeriod());
        $this->assertEquals(new Currency("EUR"), $banktransaction3->getCurrency());
        $this->assertEquals(Money::EUR(0), $banktransaction3->getStartvalue());
        $this->assertEquals(Money::EUR(0), $banktransaction3->getClosevalue());
        $this->assertEquals(0, $banktransaction3->getStatementnumber());
        $this->assertEquals("201700334", $banktransaction3->getNumber());

        $lines = $banktransaction3->getLines();
        $this->assertCount(3, $lines);

        /** @var Total $line */
        $line = $lines[0];
        $this->assertEquals("1", $line->getId());
        $this->assertEquals(LineType::TOTAL(), $line->getLineType());
        $this->assertEquals("1100", $line->getDim1());
        $this->assertEquals("debit", $line->getDebitCredit());
        $this->assertEquals(Money::EUR(0), $line->getValue());
        $this->assertEquals("2017.123456", $line->getInvoiceNumber());
        $this->assertEquals("2017.123456", $line->getDescription());
        $this->assertEquals("2017.123456", $line->getComment());

        /** @var Detail $line */
        $line = $lines[1];
        $this->assertEquals("2", $line->getId());
        $this->assertEquals(LineType::DETAIL(), $line->getLineType());
        $this->assertEquals("1800", $line->getDim1());
        $this->assertEquals("debit", $line->getDebitCredit());
        $this->assertEquals(Money::EUR(87), $line->getValue());
        $this->assertEquals("2017.123456", $line->getInvoiceNumber());
        $this->assertEquals("2017.123456", $line->getDescription());
        $this->assertEquals("2017.123456", $line->getComment());
    }

    public function testSendAllReturnsMappedObjectsAllLineTypes()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/banktransaction-with-all-line-types.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $responses = $this->apiConnector->sendAll([$this->createBankTransaction()]);
        $this->assertCount(1, $responses);
        $response = $responses[0];

        /** @var BankTransaction $banktransaction */
        $banktransaction = $response->unwrap();

        $this->assertEquals("BNK", $banktransaction->getCode());
        $this->assertEquals("OFFICE001", $banktransaction->getOffice()->getCode());
        $this->assertEquals("2017/09", $banktransaction->getPeriod());
        $this->assertEquals(new Currency("EUR"), $banktransaction->getCurrency());
        $this->assertEquals(Money::EUR(0), $banktransaction->getStartvalue());
        $this->assertEquals(Money::EUR(12100), $banktransaction->getClosevalue());
        $this->assertEquals(0, $banktransaction->getStatementnumber());
        $this->assertEquals("201700001", $banktransaction->getNumber());

        $lines = $banktransaction->getLines();
        $this->assertCount(3, $lines);

        /** @var Total $line */
        $line = $lines[0];
        $this->assertEquals("1", $line->getId());
        $this->assertEquals(LineType::TOTAL(), $line->getLineType());
        $this->assertEquals("1100", $line->getDim1());
        $this->assertEquals("debit", $line->getDebitCredit());
        $this->assertEquals(Money::EUR(12100), $line->getValue());

        /** @var Detail $line */
        $line = $lines[1];
        $this->assertEquals("2", $line->getId());
        $this->assertEquals(LineType::DETAIL(), $line->getLineType());
        $this->assertEquals("2200", $line->getDim1());
        $this->assertEquals("credit", $line->getDebitCredit());
        $this->assertEquals(Money::EUR(10000), $line->getValue());
        $this->assertEquals("My transaction", $line->getDescription());
        $this->assertEquals("VH", $line->getVatCode());
        $this->assertEquals(Money::EUR(2100), $line->getVatValue());
        $this->assertEquals(null, $line->getInvoiceNumber());
        $this->assertEquals(Money::EUR(2100), $line->getVatBaseValue());

        /** @var Vat $line */
        $line = $lines[2];
        $this->assertEquals("3", $line->getId());
        $this->assertEquals(LineType::VAT(), $line->getLineType());
        $this->assertEquals("1502", $line->getDim1());
        $this->assertEquals("credit", $line->getDebitCredit());
        $this->assertEquals(Money::EUR(2100), $line->getValue());
        $this->assertEquals("VH", $line->getVatCode());
        $this->assertEquals(Money::EUR(10000), $line->getVatTurnover());
        $this->assertEquals(Money::EUR(10000), $line->getVatBaseTurnover());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage De status van de boeking moet Concept zijn
     */
    public function testDeleteThrowsWhenResponseContainsErrorMessages()
    {
        $bookingReference = new BookingReference(Office::fromCode("OFFICE001"), "BNK", 201700006);

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturnCallback(function(\DOMDocument $document) {

                $this->assertXmlStringEqualsXmlString('<transaction action="delete" reason="It was merely a test transaction &amp; I no longer need it.">
    <office>OFFICE001</office>
    <code>BNK</code>
    <number>201700006</number>
</transaction>', $document->saveXML());

                return Response::fromString('<?xml version="1.0"?>
<transaction action="delete" reason="Test transaction" msgtype="error" msg="De status van de boeking moet Concept zijn." result="0">
    <office>OFFICE001</office>
    <code>BNK</code>
    <number>201700006</number>
</transaction>');
            });

        $this->apiConnector->delete($bookingReference, "It was merely a test transaction & I no longer need it.");
    }
}