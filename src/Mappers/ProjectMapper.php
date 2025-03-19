<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Enums\ProjectStatus;
use PhpTwinfield\Project;
use PhpTwinfield\ProjectQuantity;
use PhpTwinfield\Response\Response;

class ProjectMapper extends BaseMapper
{
    /**
     * @param Response $response
     * @return Project
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response): Project
    {
        // The tags in the dimension node
        $responseDOM = $response->getResponseDocument();

        // TODO: If name and shortname of the type attribute are needed, implement those here
        $projectTags = [
            'office' => 'setOffice',
            'type' => 'setType',
            'code' => 'setCode',
            'name' => 'setName',
            'shortname' => 'setShortName',
            'uid' => 'setUID',
            'inuse' => 'setInUse',
            'behaviour' => 'setBehaviour',
            'touched' => 'setTouched',
            'beginperiod' => 'setBeginPeriod',
            'beginyear' => 'setBeginYear',
            'endperiod' => 'setEndPeriod',
            'endyear' => 'setEndYear',
        ];

        $project = new Project;

        foreach ($projectTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$project, $method]);
        }

        // Set the status attribute
        $element = $responseDOM->documentElement;

        if (!empty($element->getAttribute("status"))) {
            $type = strtoupper($element->getAttribute("status"));
            $project->setStatus(ProjectStatus::{$type}());
        }

        // The tags in the projects node
        $projectsDOMTag = $responseDOM->getElementsByTagName('projects');
        if (isset($projectsDOMTag) && $projectsDOMTag->length > 0) {
            $projectsDOM = $projectsDOMTag->item(0);

            // TODO: test whether to use 'validtill' or 'validto' since the documentation says both
            $supplementalTags = [
                'invoicedescription' => 'setInvoiceDescription',
                'authoriser' => 'setAuthoriser',
                'customer' => 'setCustomer',
                'billable' => 'setBillable',
                'rate' => 'setRate',
                'validfrom' => 'setValidFrom',
                'validtill' => 'setValidTo',
            ];

            foreach ($supplementalTags as $tag => $method) {
                // TODO: implement the attributes on the fields here
                self::setValueFromElementTag($projectsDOM, $tag, [$project, $method]);
            }

            // Parse the quantity lines
            foreach ($projectsDOM->getElementsByTagName('quantities') as $domElement) {
                self::checkForMessage($project, $domElement);

                $quantityTags = [
                    'label' => 'setLabel',
                    'rate' => 'setRate',
                    'billable' => 'setBillable',
                    'mandatory' => 'setMandatory',
                ];

                $quantity = new ProjectQuantity;

                foreach ($quantityTags as $tag => $method) {
                    self::setValueFromElementTag($domElement, $tag, [$quantity, $method]);
                }

                $project->addQuantity($quantity);
            }
        }

        // TODO: implement the financials tag.

        return $project;
    }
}
