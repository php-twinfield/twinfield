<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\BrowseField;
use PhpTwinfield\BrowseFieldOption;

class BrowseFieldMapper extends BaseMapper
{
    /**
     * Maps a Response object to a list of BrowseField entities.
     *
     * @param Response $response
     * @return BrowseField[]
     */
    public static function map(Response $response)
    {
        $browseFields = [];

        // Get the browse definition element
        $document = $response->getResponseDocument();
        $browseFieldsElement = $document->documentElement;

        foreach ($browseFieldsElement->getElementsByTagName('browsefield') as $browseFieldElement) {
            $browseField = new BrowseField();
            $browseField
                ->setCode(self::getField($browseField, $browseFieldElement, 'code'))
                ->setDataType(self::getField($browseField, $browseFieldElement, 'datatype'));

            $finder = self::getField($browseField, $browseFieldElement, 'finder');
            if (!empty($finder)) {
                $browseField->setFinder($finder);
            }

            $canOrder = self::getField($browseField, $browseFieldElement, 'canorder');
            if (!empty($canOrder)) {
                $browseField->setCanOrder($canOrder);
            }

            foreach ($browseFieldElement->getElementsByTagName('option') as $browseFieldOptionElement) {
                $browseFieldOption = new BrowseFieldOption();
                $browseFieldOption->setCode($browseFieldOptionElement->textContent);

                $name = $browseFieldOptionElement->getAttribute('name');
                if (!empty($name)) {
                    $browseFieldOption->setName($name);
                }

                $browseField->addOption($browseFieldOption);
            }

            $browseFields[] = $browseField;
        }

        return $browseFields;
    }
}