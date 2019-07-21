<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\CustomerApiConnector;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\Currency;
use PhpTwinfield\Customer;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CustomerApiConnectorTest extends TestCase
{
    /**
     * @var CustomerApiConnector
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

        $this->apiConnector = new CustomerApiConnector($connection);

        $mockOfficeApiConnector = \Mockery::mock('overload:'.OfficeApiConnector::class)->makePartial();
        $mockOfficeApiConnector->shouldReceive('get')->andReturnUsing(function() {
            $office = new Office;
            $office->setResult(1);
            $office->setBaseCurrency(Currency::fromCode('EUR'));
            $office->setReportingCurrency(Currency::fromCode('USD'));
            return $office;
        });
    }

    private function createCustomer(): Customer
    {
        $customer = new Customer();
        return $customer;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/customer-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $customer = $this->createCustomer();

        $mapped = $this->apiConnector->send($customer);

        $this->assertInstanceOf(Customer::class, $mapped);
        $this->assertEquals("D1001", $mapped->getCode());
        $this->assertEquals("Hr E G H Küppers en/of MW M.J. Küppers-Veeneman", $mapped->getName());
    }
}
