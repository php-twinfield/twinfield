<?php

namespace PhpTwinfield\UnitTests;

use Money\Currency;
use Money\Money;
use PhpTwinfield\ApiConnectors\BankTransactionApiConnector;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Exception;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Connection;
use PHPUnit\Framework\TestCase;

class BankTransactionApiConnectorTest extends TestCase
{
    /**
     * @var BankTransactionApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $apiConnector;

    /**
     * @var Office
     */
    protected $office;

    protected function setUp()
    {
        parent::setUp();

        $this->apiConnector = $this->getMockBuilder(BankTransactionApiConnector::class)
            ->setMethods(["sendDocument"])
            ->setConstructorArgs([$this->createMock(Connection::class)])
            ->getMock();

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

        $this->apiConnector->expects($this->once())
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
    }
}