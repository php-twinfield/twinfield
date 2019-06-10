<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Project;
use PhpTwinfield\ProjectProjects;
use PhpTwinfield\ProjectQuantity;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ProjectMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Project entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Project
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Project object
        $project = new Project();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/project element
        $projectElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $project->setResult($projectElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $projectElement->getAttribute('status')));

        // Set the project elements from the project element
        $project->setBehaviour(self::parseEnumAttribute('Behaviour', self::getField($projectElement, 'behaviour', $project)))
            ->setCode(self::getField($projectElement, 'code', $project))
            ->setInUse(self::parseBooleanAttribute(self::getField($projectElement, 'name', $project)))
            ->setName(self::getField($projectElement, 'name', $project))
            ->setOffice(self::parseObjectAttribute('Office', $project, $projectElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($projectElement, 'shortname', $project))
            ->setTouched(self::getField($projectElement, 'touched', $project))
            ->setType(self::parseObjectAttribute('DimensionType', $project, $projectElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($projectElement, 'uid', $project))
            ->setVatCode(self::parseObjectAttribute('VatCode', $project, $projectElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

        // Get the projects element
        $projectsElement = $responseDOM->getElementsByTagName('projects')->item(0);

        if ($projectsElement !== null) {
            // Make a new temporary ProjectProjects class
            $projectProjects = new ProjectProjects();

            // Set the projects elements from the projects element
            $projectProjects->setAuthoriser(self::parseObjectAttribute('User', $projectProjects, $projectsElement, 'authoriser'))
                ->setBillable(self::parseBooleanAttribute(self::getField($projectsElement, 'billable', $projectProjects)))
                ->setCustomer(self::parseObjectAttribute('Customer', $projectProjects, $projectsElement, 'customer'))
                ->setInvoiceDescription(self::getField($projectsElement, 'invoicedescription', $projectProjects))
                ->setRate(self::parseObjectAttribute('Rate', $projectProjects, $projectsElement, 'rate'))
                ->setValidFrom(self::parseDateAttribute(self::getField($projectsElement, 'validfrom', $projectProjects)))
                ->setValidTill(self::parseDateAttribute(self::getField($projectsElement, 'validtill', $projectProjects)));

            // Set the projects elements from the projects element attributes
            $projectProjects->setAuthoriserInherit(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'authoriser', 'inherit')))
                ->setAuthoriserLocked(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'authoriser', 'locked')))
                ->setBillableForRatio(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'billable', 'forratio')))
                ->setBillableInherit(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'billable', 'inherit')))
                ->setBillableLocked(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'billable', 'locked')))
                ->setCustomerInherit(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'customer', 'inherit')))
                ->setCustomerLocked(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'customer', 'locked')))
                ->setRateInherit(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'rate', 'inherit')))
                ->setRateLocked(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'rate', 'locked')));

            // Get the quantities element
            $quantitiesDOMTag = $projectsElement->getElementsByTagName('quantities');

            if (isset($quantitiesDOMTag) && $quantitiesDOMTag->length > 0) {
                // Loop through each returned quantity for the project
                foreach ($quantitiesDOMTag->item(0)->childNodes as $quantityElement) {
                    if ($quantityElement->nodeType !== 1) {
                        continue;
                    }

                    // Make a new temporary ProjectQuantity class
                    $projectQuantity = new ProjectQuantity();

                    // Set the quantity elements from the quantity element
                    $projectQuantity->setBillable(self::parseBooleanAttribute(self::getField($quantityElement, 'billable', $projectQuantity)))
                        ->setLabel(self::getField($quantityElement, 'label', $projectQuantity))
                        ->setMandatory(self::parseBooleanAttribute(self::getField($quantityElement, 'mandatory', $projectQuantity)))
                        ->setRate(self::parseObjectAttribute('Rate', $projectQuantity, $quantityElement, 'rate'));

                    // Set the quantity elements from the quantity element attributes
                    $projectQuantity->setBillableLocked(self::parseBooleanAttribute(self::getAttribute($quantityElement, 'billable', 'locked')));

                    // Add the quantity to the project
                    $projectProjects->addQuantity($projectQuantity);

                    // Clean that memory!
                    unset ($projectQuantity);
                }
            }

            // Set the custom class to the project
            $project->setProjects($projectProjects);
        }

        // Return the complete object
        return $project;
    }
}
