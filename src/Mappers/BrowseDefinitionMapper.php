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
            ->setOffice(Office::fromCode(self::getField($browseDefinitionElement, 'office', $browseDefinition)))
            ->setCode(self::getField($browseDefinitionElement, 'code', $browseDefinition))
            ->setName(self::getField($browseDefinitionElement, 'name', $browseDefinition))
            ->setShortName(self::getField($browseDefinitionElement, 'shortname', $browseDefinition))
            ->setVisible(self::getField($browseDefinitionElement, 'visible', $browseDefinition));

        foreach ($browseDefinitionElement->getElementsByTagName('column') as $columnElement) {
            $browseColumn = new BrowseColumn();
            $browseColumn
                ->setId($columnElement->getAttribute('id'))
                ->setField(self::getField($columnElement, 'field', $browseColumn))
                ->setVisible(Util::parseBoolean(self::getField($columnElement, 'visible', $browseColumn)))
                ->setAsk(Util::parseBoolean(self::getField($columnElement, 'ask', $browseColumn)))
                ->setOperator(new BrowseColumnOperator(self::getField($columnElement, 'operator', $browseColumn)));

            $label = self::getField($columnElement, 'label', $browseColumn);
            if (!empty($label)) {
                $browseColumn->setLabel($label);
            }

            $from = self::getField($columnElement, 'from', $browseColumn);
            if (!empty($from)) {
                $browseColumn->setFrom($from);
            }

            $to = self::getField($columnElement, 'to', $browseColumn);
            if (!empty($to)) {
                $browseColumn->setTo($to);
            }

            $browseDefinition->addColumn($browseColumn);
        }

        return $browseDefinition;
    }
}
