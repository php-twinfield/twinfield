<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\RateApiConnector;
use PhpTwinfield\Rate;
use PhpTwinfield\RateRateChange;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class RateApiConnectorTest extends TestCase
{
    /**
     * @var RateApiConnector
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

        $this->apiConnector = new RateApiConnector($connection);
    }

    private function createRate(): Rate
    {
        $rate = new Rate();
        $rateChange = new RateRateChange();
        $rateChange->setID(2);
        $rate->addRateChange($rateChange);
        return $rate;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/rate-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $rate = $this->createRate();

        $mapped = $this->apiConnector->send($rate);

        $this->assertInstanceOf(Rate::class, $mapped);
        $this->assertEquals("DIRECT", $mapped->getCode());
        $this->assertEquals("Direct rate", $mapped->getName());
    }
}
