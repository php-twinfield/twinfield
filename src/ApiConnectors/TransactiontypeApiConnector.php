<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Services\FinderService;
use PhpTwinfield\TransactionType;
use PhpTwinfield\User;

class TransactiontypeApiConnector extends BaseApiConnector
{
    const ACCESS_RULES_DISABLED = 0;
    const ACCESS_RULES_ENABLED = 1;

    const MUTUAL_OFFICES_DISABLED = 0;
    const MUTUAL_OFFICES_ENABLED = 1;

    /**
     * List all users.
     *
     * @param string|null  $officeCode    The office code, if only users from one office should be listed
     * @param integer|null $accessRules   One of the self::ACCESS_RULES_* constants.
     * @param integer|null $mutualOffices One of the self::MUTUAL_OFFICES_* constants.
     * @param string       $pattern       The search pattern. May contain wildcards * and ?
     * @param int          $field         The search field determines which field or fields will be searched. The
     *                                    available fields depends on the finder type. Passing a value outside the
     *                                    specified values will cause an error.
     * @param int          $firstRow      First row to return, useful for paging
     * @param int          $maxRows       Maximum number of rows to return, useful for paging
     * @param array        $options       The Finder options. Passing an unsupported name or value causes an error. It's
     *                                    possible to add multiple options. An option name may be used once, specifying
     *                                    an option multiple times will cause an error.
     *
     * @return TransactionType[]
     */
    public function listAll(
        $officeCode = null,
        $accessRules = null,
        $mutualOffices = null,
        $pattern = '*',
        $field = 0,
        $firstRow = 1,
        $maxRows = 100,
        $options = array()
    ): array {
        if (!is_null($officeCode)) {
            $options['office'] = $officeCode;
        }
        if (!is_null($accessRules)) {
            $options['accessRules'] = $accessRules;
        }
        if (!is_null($mutualOffices)) {
            $options['mutualOffices'] = $mutualOffices;
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_TRANSACTION_TYPES, $pattern, $field, $firstRow, $maxRows, $options);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $transactionTypes = [];
        foreach ($response->data->Items->ArrayOfString as $transactionTypeArray) {
            $transactionType = new TransactionType();
            $transactionType->setCode($transactionTypeArray->string[0]);
            $transactionType->setName($transactionTypeArray->string[1]);
            $transactionTypes[] = $transactionType;
        }

        return $transactionTypes;
    }
}
