<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Exception;
use PhpTwinfield\LedgerAccount;
use PhpTwinfield\Services\FinderService;

class LedgerAccountApiConnector extends BaseApiConnector
{
    /**
     * List all ledger accounts.
     *
     * @param string $pattern  The search pattern. May contain wildcards * and ?
     * @param int    $field    The search field determines which field or fields will be searched. The available fields
     *                         depends on the finder type. Passing a value outside the specified values will cause an
     *                         error.
     * @param int    $firstRow First row to return, useful for paging
     * @param int    $maxRows  Maximum number of rows to return, useful for paging
     * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
     *                         to add multiple options. An option name may be used once, specifying an option multiple
     *                         times will cause an error.
     *
     * @return LedgerAccount[] The found ledger accounts.
     * @throws Exception
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $response = $this->searchFinder(
            FinderService::TYPE_DIMENSIONS_FINANCIALS,
            $pattern,
            $field,
            $firstRow,
            $maxRows,
            $options
        );

        $type = $options['dimtype'] ?? null;

        return array_map(function (array $row) use ($type): LedgerAccount {
            $ledgerAccount = new LedgerAccount();
            $ledgerAccount->setCode((string) $row[0]);
            $ledgerAccount->setName((string) ($row[1] ?? ''));
            $ledgerAccount->setType($type);

            return $ledgerAccount;
        }, self::getFinderRows($response));
    }

    /**
     * List every ledger account of one dimension type, following the Finder paging until all rows are fetched.
     *
     * @param string $dimType  LedgerAccount::TYPE_BALANCE_SHEET (BAS) or LedgerAccount::TYPE_PROFIT_AND_LOSS (PNL).
     * @param int    $pageSize Number of rows requested per Finder call.
     * @param array  $options  Additional Finder options, e.g. ['modifiedsince' => '20240101000000'].
     *
     * @return LedgerAccount[]
     * @throws Exception
     * @throws \InvalidArgumentException
     */
    public function listAllOfType(string $dimType, int $pageSize = 100, array $options = []): array
    {
        if (!in_array($dimType, [LedgerAccount::TYPE_BALANCE_SHEET, LedgerAccount::TYPE_PROFIT_AND_LOSS], true)) {
            throw new \InvalidArgumentException("Ledger accounts are dimensions of type BAS or PNL, '{$dimType}' given.");
        }

        if ($pageSize < 1) {
            throw new \InvalidArgumentException('The page size should be a positive integer.');
        }

        $options['dimtype'] = $dimType;

        $ledgerAccounts = [];
        $firstRow = 1;

        do {
            $response = $this->searchFinder(
                FinderService::TYPE_DIMENSIONS_FINANCIALS,
                '*',
                FinderService::SEARCH_FIELD_CODE_OR_NAME,
                $firstRow,
                $pageSize,
                $options
            );

            $totalRows = (int) ($response->data->TotalRows ?? 0);
            $rows = self::getFinderRows($response);

            foreach ($rows as $row) {
                $ledgerAccount = new LedgerAccount();
                $ledgerAccount->setCode((string) $row[0]);
                $ledgerAccount->setName((string) ($row[1] ?? ''));
                $ledgerAccount->setType($dimType);
                $ledgerAccounts[] = $ledgerAccount;
            }

            $firstRow += count($rows);
        } while ($rows !== [] && $firstRow <= $totalRows);

        return $ledgerAccounts;
    }

    /**
     * List all balance sheet (BAS) and profit and loss (PNL) accounts of the office.
     *
     * @return LedgerAccount[]
     * @throws Exception
     */
    public function listAllBalanceSheetAndProfitAndLoss(int $pageSize = 100, array $options = []): array
    {
        return array_merge(
            $this->listAllOfType(LedgerAccount::TYPE_BALANCE_SHEET, $pageSize, $options),
            $this->listAllOfType(LedgerAccount::TYPE_PROFIT_AND_LOSS, $pageSize, $options)
        );
    }
}
