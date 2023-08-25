<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Category;
use PhpTwinfield\Office;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\TransactionType;

class TransactionTypeApiConnector extends BaseApiConnector
{
    /**
     * List all transaction types (daybooks)
     *
     * @param Office|null   $office   The office code, if only transaction types from one office should be listed
     * @param bool          $hidden   If set also hidden transaction types are returned.
     * @param bool          $ic       Shows only “InterCompany” transaction types in the result set. This option cannot
     *                                be used in combination with the 'hidden' option.
     * @param Category|null $category The office code, if only transaction types from one office should be listed
     * @param string        $pattern  The search pattern. May contain wildcards * and ?
     * @param int           $field    The search field determines which field or fields will be searched. The
     *                                available fields depends on the finder type. Passing a value outside the
     *                                specified values will cause an error.
     * @param int           $firstRow First row to return, useful for paging
     * @param int           $maxRows  Maximum number of rows to return, useful for paging
     *
     * @return TransactionType[]
     */
    public function listAll(
        ?Office $office = null,
        bool $hidden = false,
        bool $ic = false,
        ?Category $category = null,
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100
    ): array {
        $options = [];
        if (!is_null($office)) {
            $options['office'] = $office->getCode();
        }

        if ($hidden && $ic) {
            throw new \InvalidArgumentException("The 'ic' option cannot be used in combination with the 'hidden' option");
        } elseif ($hidden) {
            $options['hidden'] = 1;
        } elseif ($ic) {
            $options['ic'] = 1;
        }

        if (!is_null($category)) {
            $options['category'] = $category->getValue();
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_TRANSACTION_TYPES, $pattern, $field, $firstRow, $maxRows, $options);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $transactionTypes = [];
        foreach ($response->data->Items->ArrayOfString as $transactionTypeArray) {
            $transactionType = new TransactionType();

            if (is_array($transactionTypeArray)) {
                $transactionType->setCode($transactionTypeArray[0]);
                $transactionType->setName($transactionTypeArray[1]);
            } else {
                $transactionType->setCode($transactionTypeArray->string[0]);
                $transactionType->setName($transactionTypeArray->string[1]);
            }

            $transactionTypes[] = $transactionType;
        }

        return $transactionTypes;
    }
}
