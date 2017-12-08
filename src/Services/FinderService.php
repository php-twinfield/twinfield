<?php

namespace PhpTwinfield\Services;

class FinderService extends BaseService
{
    protected function WSDL(): string
    {
        return '/webservices/finder.asmx?wsdl';
    }

    /**
     * @param string $type One of the self::TYPE_* constants.
     * @param string $pattern The search pattern. May contain wildcards * and ?
     * @param int $field One of the self::SEARCH_FIELD_* constants. The search field determines which field or
     *                         fields will be searched. The available fields depends on the finder type. Passing a value
     *                         outside the specified values will cause an error.
     * @param int $firstRow First row to return, useful for paging.
     * @param int $maxRows Maximum number of rows to return, useful for paging.
     * @param array $options The Finder options. Passing an unsupported name or value causes an error. It's possible
     *                         to add multiple options. An option name may be used once, specifying an option multiple
     *                         times will cause an error.
     * @return \stdClass
     */
    public function searchFinder(
        string $type,
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): \stdClass {
        return $this->Search(
            [
                'type' => $type,
                'pattern' => $pattern,
                'field' => $field,
                'firstRow' => $firstRow,
                'maxRows' => $maxRows,
                'options' => $options,
            ]
        );
    }
}