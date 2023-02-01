<?php

declare(strict_types=1);

namespace IntegrationTests;

use PhpTwinfield\ApiConnectors\TransactionTypeApiConnector;
use PhpTwinfield\Enums\Category;
use PhpTwinfield\IntegrationTests\BaseIntegrationTest;
use PhpTwinfield\Services\FinderService;

final class TransactionTypesIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var TransactionTypeApiConnector()
     */
    private $transactionTypeApiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionTypeApiConnector = new TransactionTypeApiConnector($this->connection);
    }

    public function testListAllWorks()
    {
        $response = require __DIR__ . '/resources/transactionTypesListAllResponse.php';

        $this->finderService
            ->expects($this->once())
            ->method("searchFinder")
            ->with(FinderService::TYPE_TRANSACTION_TYPES, '*', 0, 1, 100, [])
            ->willReturn($response);

        $transactionTypes = $this->transactionTypeApiConnector->listAll();

        $this->assertSame('BEGINBALANS', $transactionTypes[0]->getCode());
        $this->assertSame('Beginbalans', $transactionTypes[0]->getName());
        $this->assertSame('BNK', $transactionTypes[1]->getCode());
        $this->assertSame('Standaard bank', $transactionTypes[1]->getName());
        $this->assertSame('INK', $transactionTypes[2]->getCode());
        $this->assertSame('Inkoopfactuur', $transactionTypes[2]->getName());
        $this->assertSame('KAS', $transactionTypes[3]->getCode());
        $this->assertSame('Kas', $transactionTypes[3]->getName());
        $this->assertSame('VRK', $transactionTypes[4]->getCode());
        $this->assertSame('Verkoopfactuur', $transactionTypes[4]->getName());
    }

    public function testListAllTheIcOptionCannotBeUsedInCombinationWithTheHiddenOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->transactionTypeApiConnector->listAll(null, true, true);
    }

    public function testListAllWithCategory()
    {
        $response = require __DIR__ . '/resources/transactionTypesListAllResponse.php';

        $this->finderService
            ->expects($this->once())
            ->method("searchFinder")
            ->with(FinderService::TYPE_TRANSACTION_TYPES, '*', 0, 1, 100, [
                'category' => 'sales',
            ])
            ->willReturn($response);

        $this->transactionTypeApiConnector->listAll(null, false, false, Category::SALES());
    }

    public function testListAllWithTheIcOption()
    {
        $response = require __DIR__ . '/resources/transactionTypesListAllResponse.php';

        $this->finderService
            ->expects($this->once())
            ->method("searchFinder")
            ->with(FinderService::TYPE_TRANSACTION_TYPES, '*', 0, 1, 100, [
                'ic' => '1',
            ])
            ->willReturn($response);

        $this->transactionTypeApiConnector->listAll(null, false, true);
    }

    public function testListAllWithTheHiddenOption()
    {
        $response = require __DIR__ . '/resources/transactionTypesListAllResponse.php';

        $this->finderService
            ->expects($this->once())
            ->method("searchFinder")
            ->with(FinderService::TYPE_TRANSACTION_TYPES, '*', 0, 1, 100, [
                'hidden' => '1',
            ])
            ->willReturn($response);

        $this->transactionTypeApiConnector->listAll(null, true);
    }
}
