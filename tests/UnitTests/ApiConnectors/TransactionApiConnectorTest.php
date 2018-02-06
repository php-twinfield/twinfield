<?php

namespace PhpTwinfield\UnitTests;

use Money\Currency;
use PhpTwinfield\ApiConnectors\TransactionApiConnector;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class TransactionApiConnectorTest extends TestCase
{
    /**
     * @var TransactionApiConnector
     */
    protected $apiConnector;

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processXmlService;

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

        $this->apiConnector = new TransactionApiConnector($connection);
    }

    private function createTransaction(string $transactionClassName): BaseTransaction
    {
        /** @var BaseTransaction $transaction */
        $transaction = new $transactionClassName;
        $transaction->setDestiny(Destiny::TEMPORARY());
        $transaction->setCode("VRK");
        $transaction->setDate(new \DateTimeImmutable("2017-09-01"));
        $transaction->setOffice(Office::fromCode("DEV1000"));
        return $transaction;
    }

    public function testSendReturnsMappedObjects()
    {
        $response = Response::fromString('<?xml version="1.0"?>
<transactions result="1">
	<transaction result="1" location="temporary">
		<header>
			<code name="Verkoopfactuur" shortname="Verkoop">VRK</code>
			<currency name="Euro" shortname="Euro">EUR</currency>
			<date>20170901</date>
			<period>2017/09</period>
			<invoicenumber>INV123458</invoicenumber>
			<office name="Development BV" shortname="Development BV">DEV1000</office>
			<origin>import</origin>
			<user name="Consodo-Super" shortname="Consodo-Super">Consodo-SUPER</user>
			<regime>generic</regime>
			<duedate>20171001</duedate>
			<number>201702412</number>
		</header>
		<lines>
			<line type="total" id="1">
				<dim1 name="Debiteuren" shortname="" type="BAS" inuse="true" vatcode="" vatobligatory="false">130000
				</dim1>
				<dim2 name="Test 2" shortname="" type="DEB" inuse="true" vatcode="" vatobligatory="false">Dxxxx</dim2>
				<debitcredit>debit</debitcredit>
				<value>100.00</value>
				<description/>
				<rate>1</rate>
				<basevalue>100.00</basevalue>
				<reprate>1</reprate>
				<repvalue>100.00</repvalue>
				<vattotal>0.00</vattotal>
				<vatbasetotal>0.00</vatbasetotal>
				<matchlevel>2</matchlevel>
				<customersupplier>2</customersupplier>
				<openvalue>100.00</openvalue>
				<openbasevalue>100.00</openbasevalue>
				<openrepvalue>100.00</openrepvalue>
				<matchstatus>available</matchstatus>
			</line>
			<line type="detail" id="2">
				<dim1 name="Tussenrekening transitorisch boeken" shortname="" type="BAS" inuse="true" vatcode=""
					  vatobligatory="false">191000
				</dim1>
				<debitcredit>credit</debitcredit>
				<value>100.00</value>
				<description>Outfit</description>
				<rate>1</rate>
				<basevalue>100.00</basevalue>
				<reprate>1</reprate>
				<repvalue>100.00</repvalue>
				<currencydate/>
				<matchstatus>notmatchable</matchstatus>
			</line>
		</lines>
	</transaction>
</transactions>');

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $transaction = $this->createTransaction(SalesTransaction::class);

        $mapped = $this->apiConnector->send($transaction);

        $this->assertEquals("VRK", $mapped->getCode());
        $this->assertEquals(new Currency("EUR"), $mapped->getCurrency());
        $this->assertEquals("2017/09", $mapped->getPeriod());
        $this->assertEquals("INV123458", $mapped->getInvoiceNumber());
        $this->assertEquals(new \DateTimeImmutable("2017-09-01"), $mapped->getDate());
    }
}