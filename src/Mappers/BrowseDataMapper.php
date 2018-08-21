<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Exception;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;
use PhpTwinfield\BrowseData;
use PhpTwinfield\BrowseDataCell;
use PhpTwinfield\BrowseDataHeader;
use PhpTwinfield\BrowseDataRow;

class BrowseDataMapper extends BaseMapper
{
    /**
     * Maps a Response object to a BrowseData entity.
     *
     * @param Response $response
     * @return BrowseData
     * @throws Exception
     */
    public static function map(Response $response)
    {
        // Generate new BrowseData object
        $browseData = new BrowseData();

        // Get the browse data element
        $document = $response->getResponseDocument();
        $browseDataElement = $document->documentElement;

        $browseData
            ->setFirst((int)$browseDataElement->getAttribute('first'))
            ->setLast((int)$browseDataElement->getAttribute('last'))
            ->setTotal((int)$browseDataElement->getAttribute('total'));

        // Get headers
        $headersElement = $browseDataElement->getElementsByTagName('th')[0];
        foreach ($headersElement->getElementsByTagName('td') as $headerElement) {
            $browseDataHeader = new BrowseDataHeader();
            $browseDataHeader
                ->setLabel($headerElement->getAttribute('label'))
                ->setHideForUser(Util::parseBoolean($headerElement->getAttribute('hideforuser')))
                ->setType($headerElement->getAttribute('type'))
                ->setCode($headerElement->textContent);

            $browseData->addHeader($browseDataHeader);
        }

        // Get rows
        foreach ($browseDataElement->getElementsByTagName('tr') as $rowElement) {
            $browseDataRow = new BrowseDataRow();

            // Get row key
            $keyElement = $rowElement->getElementsByTagName('key')[0];

            $browseDataRow
                ->setOffice(Office::fromCode(self::getField($keyElement, 'office')))
                ->setCode(self::getField($keyElement, 'code'))
                ->setNumber(self::getField($keyElement, 'number'))
                ->setLine(self::getField($keyElement, 'line'));

            $browseData->addRow($browseDataRow);

            // Get cells
            foreach ($rowElement->getElementsByTagName('td') as $cellElement) {
                $browseDataCell = new BrowseDataCell();
                $browseDataCell
                    ->setField($cellElement->getAttribute('field'))
                    ->setHideForUser(Util::parseBoolean($cellElement->getAttribute('hideforuser')))
                    ->setType($cellElement->getAttribute('type'))
                    ->setValue(
                    self::parseBrowseDataValue($browseDataCell->getType(), $cellElement->textContent)
                );

                $browseDataRow->addCell($browseDataCell);
            }
        }

        return $browseData;
    }

    /**
     * Parse value of browse data to the given type.
     *
     * @param string $type
     * @param string $value
     * @return mixed
     * @throws Exception
     */
    private static function parseBrowseDataValue(string $type, string $value)
    {
        switch ($type) {
            case 'Long':
                return $value; // Return a long as a string because casting to an int can cause problems for 32 bit PHP
            case 'Decimal':
            case 'Value':
                return floatval($value);
            case 'Date':
                return Util::parseDate($value);
            case 'Datetime':
                return Util::parseDateTime($value);
            default:
                return $value;
        }
    }
}
