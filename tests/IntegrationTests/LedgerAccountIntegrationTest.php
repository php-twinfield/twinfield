<?php

declare(strict_types=1);

namespace IntegrationTests;

use PhpTwinfield\ApiConnectors\ApiOptions;
use PhpTwinfield\ApiConnectors\LedgerAccountApiConnector;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\IntegrationTests\BaseIntegrationTest;
use PhpTwinfield\LedgerAccount;
use PhpTwinfield\Services\FinderService;

final class LedgerAccountIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var LedgerAccountApiConnector
     */
    private $ledgerAccountApiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ledgerAccountApiConnector = new LedgerAccountApiConnector($this->connection);
    }

    /**
     * @param string[][] $rows
     */
    private static function finderResponse(int $totalRows, array $rows): \stdClass
    {
        return (object) [
            'SearchResult' => (object) [],
            'data' => (object) [
                'TotalRows' => $totalRows,
                'Columns' => (object) ['string' => ['Code', 'Naam']],
                'Items' => (object) [
                    'ArrayOfString' => array_map(function (array $row) {
                        return (object) ['string' => $row];
                    }, $rows),
                ],
            ],
        ];
    }

    public function testListAllOfTypePagesThroughAllRows()
    {
        $this->finderService
            ->expects($this->exactly(2))
            ->method('searchFinder')
            ->withConsecutive(
                [FinderService::TYPE_DIMENSIONS_FINANCIALS, '*', 0, 1, 2, ['dimtype' => 'BAS']],
                [FinderService::TYPE_DIMENSIONS_FINANCIALS, '*', 0, 3, 2, ['dimtype' => 'BAS']]
            )
            ->willReturnOnConsecutiveCalls(
                self::finderResponse(3, [['0100', 'Gebouwen'], ['1000', 'Kas']]),
                self::finderResponse(3, [['1100', 'Bank']])
            );

        $accounts = $this->ledgerAccountApiConnector->listAllOfType(LedgerAccount::TYPE_BALANCE_SHEET, 2);

        $this->assertCount(3, $accounts);
        $this->assertSame(['0100', '1000', '1100'], array_map(function (LedgerAccount $account) {
            return $account->getCode();
        }, $accounts));
        $this->assertSame('Bank', $accounts[2]->getName());
        $this->assertSame('BAS', $accounts[2]->getType());
        $this->assertTrue($accounts[2]->isBalanceSheet());
        $this->assertFalse($accounts[2]->isProfitAndLoss());
    }

    public function testListAllOfTypeHandlesSingleRowObject()
    {
        $response = self::finderResponse(1, []);
        $response->data->Items->ArrayOfString = (object) ['string' => ['8000', 'Omzet']];

        $this->finderService
            ->expects($this->once())
            ->method('searchFinder')
            ->with(FinderService::TYPE_DIMENSIONS_FINANCIALS, '*', 0, 1, 100, ['dimtype' => 'PNL', 'modifiedsince' => '20240101000000'])
            ->willReturn($response);

        $accounts = $this->ledgerAccountApiConnector->listAllOfType(
            LedgerAccount::TYPE_PROFIT_AND_LOSS,
            100,
            ['modifiedsince' => '20240101000000']
        );

        $this->assertCount(1, $accounts);
        $this->assertSame('8000', $accounts[0]->getCode());
        $this->assertSame('Omzet', $accounts[0]->getName());
        $this->assertTrue($accounts[0]->isProfitAndLoss());
    }

    public function testListAllOfTypeReturnsEmptyArrayWithoutRows()
    {
        $this->finderService
            ->expects($this->once())
            ->method('searchFinder')
            ->willReturn((object) ['data' => (object) ['TotalRows' => 0]]);

        $this->assertSame([], $this->ledgerAccountApiConnector->listAllOfType(LedgerAccount::TYPE_PROFIT_AND_LOSS));
    }

    public function testListAllOfTypeRejectsNonLedgerDimensionTypes()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->ledgerAccountApiConnector->listAllOfType('DEB');
    }

    public function testListAllOfTypeRejectsInvalidPageSize()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->ledgerAccountApiConnector->listAllOfType(LedgerAccount::TYPE_BALANCE_SHEET, 0);
    }

    public function testListAllBalanceSheetAndProfitAndLossCombinesBothTypes()
    {
        $this->finderService
            ->expects($this->exactly(2))
            ->method('searchFinder')
            ->withConsecutive(
                [FinderService::TYPE_DIMENSIONS_FINANCIALS, '*', 0, 1, 100, ['dimtype' => 'BAS']],
                [FinderService::TYPE_DIMENSIONS_FINANCIALS, '*', 0, 1, 100, ['dimtype' => 'PNL']]
            )
            ->willReturnOnConsecutiveCalls(
                self::finderResponse(1, [['1000', 'Kas']]),
                self::finderResponse(1, [['8000', 'Omzet']])
            );

        $accounts = $this->ledgerAccountApiConnector->listAllBalanceSheetAndProfitAndLoss();

        $this->assertSame('BAS', $accounts[0]->getType());
        $this->assertSame('PNL', $accounts[1]->getType());
    }

    public function testListAllKeepsWorkingWithArrayRows()
    {
        $response = require __DIR__ . '/resources/transactionTypesListAllWithMultipleResultsResponse.php';

        $this->finderService
            ->expects($this->once())
            ->method('searchFinder')
            ->with(FinderService::TYPE_DIMENSIONS_FINANCIALS, '*', 0, 1, 100, [])
            ->willReturn($response);

        $accounts = $this->ledgerAccountApiConnector->listAll();

        $this->assertSame('BEGINBALANS', $accounts[0]->getCode());
        $this->assertSame('Beginbalans', $accounts[0]->getName());
        $this->assertNull($accounts[0]->getType());
    }

    public function testFinderRequestIsRetriedAfterRateLimitUsingRetryAfterHeader()
    {
        $finderService = $this->createPartialMock(FinderService::class, ['searchFinder', '__getLastResponseHeaders']);
        $finderService->expects($this->exactly(2))
            ->method('searchFinder')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new \SoapFault('Client', 'Too Many Requests')),
                self::finderResponse(1, [['8000', 'Omzet']])
            );
        $finderService->expects($this->once())
            ->method('__getLastResponseHeaders')
            ->willReturn("HTTP/1.1 429 Too Many Requests\r\nRetry-After: 7\r\n");

        $connection = $this->createMock(\PhpTwinfield\Secure\AuthenticatedConnection::class);
        $connection->method('getAuthenticatedClient')->with(Services::FINDER())->willReturn($finderService);
        $connection->expects($this->once())->method('resetClient')->with(Services::FINDER());

        $connector = $this->getMockBuilder(LedgerAccountApiConnector::class)
            ->setConstructorArgs([$connection, new ApiOptions(null, null, true)])
            ->onlyMethods(['sleep'])
            ->getMock();
        $connector->expects($this->once())->method('sleep');

        $accounts = $connector->listAllOfType(LedgerAccount::TYPE_PROFIT_AND_LOSS);

        $this->assertCount(1, $accounts);
    }

    public function testFinderRequestIsNotRetriedForNonRetriableErrors()
    {
        $finderService = $this->createPartialMock(FinderService::class, ['searchFinder']);
        $finderService->expects($this->once())
            ->method('searchFinder')
            ->willThrowException(new \SoapFault('Server', 'Access denied'));

        $connection = $this->createMock(\PhpTwinfield\Secure\AuthenticatedConnection::class);
        $connection->method('getAuthenticatedClient')->willReturn($finderService);

        $connector = new LedgerAccountApiConnector($connection, new ApiOptions(null, null, true));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Access denied');

        $connector->listAllOfType(LedgerAccount::TYPE_BALANCE_SHEET);
    }

    public function testFinderRequestStopsAfterMaxRetries()
    {
        $finderService = $this->createPartialMock(FinderService::class, ['searchFinder']);
        $finderService->expects($this->exactly(3))
            ->method('searchFinder')
            ->willThrowException(new \SoapFault('Client', 'SSL: Connection reset by peer'));

        $connection = $this->createMock(\PhpTwinfield\Secure\AuthenticatedConnection::class);
        $connection->method('getAuthenticatedClient')->willReturn($finderService);
        $connection->expects($this->exactly(3))->method('resetClient')->with(Services::FINDER());

        $connector = new LedgerAccountApiConnector($connection, new ApiOptions(null, 2));

        $this->expectException(Exception::class);

        $connector->listAllOfType(LedgerAccount::TYPE_BALANCE_SHEET);
    }
}
