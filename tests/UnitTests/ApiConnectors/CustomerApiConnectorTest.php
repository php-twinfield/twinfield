<?php

namespace PhpTwinfield\UnitTests;

use Money\Currency;
use Money\Money;
use PhpTwinfield\ApiConnectors\BankTransactionApiConnector;
use PhpTwinfield\ApiConnectors\CustomerApiConnector;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Customer;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Connection;
use PHPUnit\Framework\TestCase;

class CustomerApiConnectorTest extends TestCase
{
    /**
     * @var CustomerApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $apiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->apiConnector = $this->getMockBuilder(CustomerApiConnector::class)
            ->setMethods(["sendDocument"])
            ->setConstructorArgs([$this->createMock(Connection::class)])
            ->getMock();
    }

    private function createCustomer(): Customer
    {
        $customer = new Customer();
        return $customer;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/customers-response.xml"
        ));

        $this->apiConnector->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $customer = $this->createCustomer();

        $mapped = $this->apiConnector->send($customer);

        $this->assertInstanceOf(Customer::class, $mapped);
        $this->assertEquals("D1001", $mapped->getCode());
        $this->assertEquals("Hr E G H Küppers en/of MW M.J. Küppers-Veeneman", $mapped->getName());
        $this->assertEquals("BE", $mapped->getCountry());
    }
}