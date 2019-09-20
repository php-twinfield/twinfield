<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Services\FinderService;
use PhpTwinfield\GeneralLedgerAccount;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * General Ledger Accounts.
 */
class GeneralLedgerAccountsConnector extends BaseApiConnector
{
    /**
     * List all General Ledger Accounts.
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
     * @return array The General Ledger Accounts found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $response = $this->getFinderService()->searchFinder(
            FinderService::TYPE_DIMENSIONS_PROJECTS,
            $pattern,
            $field,
            $firstRow,
            $maxRows,
            $options
        );

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $generalLedgerAccounts = [];
        foreach ($response->data->Items->ArrayOfString as $generalLedgerAccountArray) {
            $generalLedgerAccount = new GeneralLedgerAccount();
            $generalLedgerAccount->setCode($generalLedgerAccountArray->string[0]);
            $generalLedgerAccount->setName($generalLedgerAccountArray->string[1]);
            $generalLedgerAccounts[] = $generalLedgerAccount;
        }

        return $generalLedgerAccounts;
    }
}
