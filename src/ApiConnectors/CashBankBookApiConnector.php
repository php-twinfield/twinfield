<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\CashBankBook;
use PhpTwinfield\Exception;
use PhpTwinfield\Services\FinderService;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Cash and Bank books.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CashBankBookApiConnector extends BaseApiConnector
{
    /**
     * List all cash and bank books.
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
     * @return CashBankBook[] The cash and bank books found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_CASHBOOKS_AND_BANKS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $cashBankBookListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(CashBankBook::class, $response->data, $cashBankBookListAllTags);
    }
}
