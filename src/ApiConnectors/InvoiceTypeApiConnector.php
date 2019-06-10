<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Exception;
use PhpTwinfield\InvoiceType;
use PhpTwinfield\Services\FinderService;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * InvoiceTypes.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class InvoiceTypeApiConnector extends BaseApiConnector
{
    /**
     * List all Invoice types.
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
     * @return InvoiceType[] The Invoice types found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_INVOICE_TYPES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $invoiceTypeListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(\PhpTwinfield\InvoiceType::class, $response->data, $invoiceTypeListAllTags);
    }

    /**
     * Determine if a specified invoice type is of the inclusive or exclusive VAT type.
     *
     * @param string $pattern  The search pattern. May contain wildcards * and ?, but should be the full code of the invoice type
     *
     * @return string Is the invoice type of the inclusive or exclusive VAT type.
     */
    public function getInvoiceTypeVatType(
        string $pattern
    ): ?string {
        $options = array('vat' => 'inclusive');
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_INVOICE_TYPES, $pattern, 1, 1, 1, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return 'exclusive';
        }

        foreach ($response->data->Items->ArrayOfString as $invoiceTypeArray) {
            if (isset($invoiceTypeArray->string[0])) {
                if ($pattern === $invoiceTypeArray->string[0]) {
                    return 'inclusive';
                }
            } else {
                if ($pattern === $invoiceTypeArray[0]) {
                    return 'inclusive';
                }
            }
        }

        return 'exclusive';
    }
}