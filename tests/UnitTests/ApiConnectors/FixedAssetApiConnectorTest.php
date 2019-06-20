<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\FixedAssetApiConnector;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\Currency;
use PhpTwinfield\FixedAsset;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FixedAssetApiConnectorTest extends TestCase
{
    /**
     * @var FixedAssetApiConnector
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

        $this->apiConnector = new FixedAssetApiConnector($connection);
        
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

    private function createFixedAsset(): FixedAsset
    {
        $fixedAsset = new FixedAsset();
        return $fixedAsset;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/fixedasset-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $fixedAsset = $this->createFixedAsset();

        $mapped = $this->apiConnector->send($fixedAsset);

        $this->assertInstanceOf(FixedAsset::class, $mapped);
        $this->assertEquals("60000", $mapped->getCode());
        $this->assertEquals("Afschrijving Computer", $mapped->getName());
    }
}
