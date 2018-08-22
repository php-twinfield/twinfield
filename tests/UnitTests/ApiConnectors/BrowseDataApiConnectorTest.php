<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\BrowseDataApiConnector;
use PhpTwinfield\BrowseColumn;
use PhpTwinfield\BrowseSortField;
use PhpTwinfield\Enums\BrowseColumnOperator;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class BrowseDataApiConnectorTest extends TestCase
{
    /**
     * @var BrowseDataApiConnector
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

        $this->apiConnector = new BrowseDataApiConnector($connection);
    }

    public function testFailureResponseForGetBrowseDataWithoutColumns()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Expected an array to contain at least 1 elements. Got: 0');

        $this->apiConnector->getBrowseData('001', []);
    }

    public function testFailureResponseForGetBrowseDataWithWrongColumnObjects()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Expected an instance of PhpTwinfield\BrowseColumn. Got: PhpTwinfield\BrowseSortField');

        $this->apiConnector->getBrowseData('001', [new BrowseSortField('test')]);
    }

    public function testFailureResponseForGetBrowseDataWithWrongSortFieldsObjects()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Expected an instance of PhpTwinfield\BrowseSortField. Got: PhpTwinfield\BrowseColumn');

        $columns[] = (new BrowseColumn())
            ->setField('fin.trs.head.yearperiod')
            ->setLabel('Period')
            ->setVisible(true)
            ->setAsk(true)
            ->setOperator(BrowseColumnOperator::BETWEEN())
            ->setFrom('2013/01')
            ->setTo('2013/12');

        $this->apiConnector->getBrowseData('001', $columns, $columns);
    }
}