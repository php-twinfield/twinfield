<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Enums\BrowseColumnOperator;
use PhpTwinfield\Exception;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;
use PhpTwinfield\BrowseColumn;
use PhpTwinfield\BrowseDefinition;

class BrowseDefinitionMapper extends BaseMapper
{
    /**
     * Maps a Response object to a BrowseDefinition entity.
     *
     * @param Response $response
     * @throws Exception
     * @return BrowseDefinition
     */
    public static function map(Response $response)
    {
        // Generate new BrowseData object
        $browseDefinition = new BrowseDefinition();

        // Get the browse definition element
        $document = $response->getResponseDocument();
        $browseDefinitionElement = $document->documentElement;

        $browseDefinition
            ->setOffice(Office::fromCode(self::getField($browseDefinition, $browseDefinitionElement, 'office')))
            ->setCode(self::getField($browseDefinition, $browseDefinitionElement, 'code'))
            ->setName(self::getField($browseDefinition, $browseDefinitionElement, 'name'))
            ->setShortName(self::getField($browseDefinition, $browseDefinitionElement, 'shortname'))
            ->setVisible(self::getField($browseDefinition, $browseDefinitionElement, 'visible'));

        foreach ($browseDefinitionElement->getElementsByTagName('column') as $columnElement) {
            $browseColumn = new BrowseColumn();
            $browseColumn
                ->setId($columnElement->getAttribute('id'))
                ->setField(self::getField($browseColumn, $columnElement, 'field'))
                ->setVisible(Util::parseBoolean(self::getField($browseColumn, $columnElement, 'visible')))
                ->setAsk(Util::parseBoolean(self::getField($browseColumn, $columnElement, 'ask')))
                ->setOperator(new BrowseColumnOperator(self::getField($browseColumn, $columnElement, 'operator')));

            $label = self::getField($browseColumn, $columnElement, 'label');
            if (!empty($label)) {
                $browseColumn->setLabel($label);
            }

            $from = self::getField($browseColumn, $columnElement, 'from');
            if (!empty($from)) {
                $browseColumn->setFrom($from);
            }

            $to = self::getField($browseColumn, $columnElement, 'to');
            if (!empty($to)) {
                $browseColumn->setTo($to);
            }

            $browseDefinition->addColumn($browseColumn);
        }

        return $browseDefinition;
    }
}
