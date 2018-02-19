<?php

namespace PhpTwinfield\UnitTests;

use Money\Currency;
use Money\Money;
use PhpTwinfield\ApiConnectors\BankTransactionApiConnector;
use PhpTwinfield\ApiConnectors\MatchesApiConnector;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchCode;
use PhpTwinfield\Exception;
use PhpTwinfield\MatchLine;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;
use PhpTwinfield\Transactions\BankTransactionLine\Vat;
use PHPUnit\Framework\TestCase;

class MatchesApiConnectorTest extends TestCase
{
    /**
     * @var MatchesApiConnector
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

        $this->apiConnector = new MatchesApiConnector($connection);
    }

    private function createMatchSet(): MatchSet
    {
        $matchSet = new MatchSet();
        $matchSet->setOffice(Office::fromCode("OFFICE001"));
        $matchSet->setMatchCode(MatchCode::CUSTOMERS());
        $matchSet->setMatchDate(new \DateTimeImmutable("2018-02-19"));

        return $matchSet;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/single-matchset.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $responses = $this->apiConnector->sendAll([
            $this->createMatchSet(),
            $this->createMatchSet()
        ]);

        $this->assertCount(2, $responses);

        self::assertSame(1, $responses->countFailedResponses());
        self::assertSame(1, $responses->countSuccessfulResponses());

        [$response1, $response2] = $responses;

        /** @var MatchSet $matchSet */
        $matchSet = $response1->unwrap();

        $this->assertEquals("OFFICE001", $matchSet->getOffice()->getCode());
        $this->assertEquals(MatchCode::CUSTOMERS(), $matchSet->getMatchCode());
        $this->assertEquals("2018-02-19", $matchSet->getMatchDate()->format('Y-m-d'));

        $lines = $matchSet->getLines();
        $this->assertCount(2, $lines);

        $line = $lines[0];
        $this->assertEquals("VRK", $line->getTranscode());
        $this->assertSame(201700103, $line->getTransnumber());
        $this->assertSame(1, $line->getTransline());
        $this->assertEquals(Money::EUR(526), $line->getMatchValue());

        $line = $lines[1];
        $this->assertEquals("DUM2", $line->getTranscode());
        $this->assertSame(201700029, $line->getTransnumber());
        $this->assertSame(79, $line->getTransline());
        $this->assertEquals(Money::EUR(-526), $line->getMatchValue());

        /** @var MatchSet $matchSet */
        try {
            $matchSet = $response2->unwrap();
        } catch (ResponseException $e) {
            $matchSet = $e->getReturnedObject();
        }

        $this->assertEquals("OFFICE001", $matchSet->getOffice()->getCode());
        $this->assertEquals(MatchCode::CUSTOMERS(), $matchSet->getMatchCode());
        $this->assertEquals("2018-02-19", $matchSet->getMatchDate()->format('Y-m-d'));

        $lines = $matchSet->getLines();
        $this->assertCount(2, $lines);

        $line = $lines[0];
        $this->assertEquals("VRK", $line->getTranscode());
        $this->assertSame(201700100, $line->getTransnumber());
        $this->assertSame(1, $line->getTransline());
        $this->assertEquals(Money::EUR(140), $line->getMatchValue());

        $line = $lines[1];
        $this->assertEquals("DUM2", $line->getTranscode());
        $this->assertSame(201700029, $line->getTransnumber());
        $this->assertSame(64, $line->getTransline());
        $this->assertEquals(Money::EUR(-140), $line->getMatchValue());
    }
}
