<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Project;
use PhpTwinfield\ProjectProjects;
use PhpTwinfield\ProjectQuantity;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

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
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $projectElement->getAttribute('status')));

        // Set the project elements from the project element
        $project->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($projectElement, 'behaviour', $project)))
            ->setCode(self::getField($projectElement, 'code', $project))
            ->setInUse(Util::parseBoolean(self::getField($projectElement, 'name', $project)))
            ->setName(self::getField($projectElement, 'name', $project))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $project, $projectElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($projectElement, 'shortname', $project))
            ->setTouched(self::getField($projectElement, 'touched', $project))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $project, $projectElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($projectElement, 'uid', $project))
            ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $project, $projectElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

        // Get the projects element
        $projectsElement = $responseDOM->getElementsByTagName('projects')->item(0);

        if ($projectsElement !== null) {
            // Make a new temporary ProjectProjects class
            $projectProjects = new ProjectProjects();

            // Set the projects elements from the projects element
            $projectProjects->setAuthoriser(self::parseObjectAttribute(\PhpTwinfield\User::class, $projectProjects, $projectsElement, 'authoriser'))
                ->setBillable(Util::parseBoolean(self::getField($projectsElement, 'billable', $projectProjects)))
                ->setCustomer(self::parseObjectAttribute(\PhpTwinfield\Customer::class, $projectProjects, $projectsElement, 'customer'))
                ->setInvoiceDescription(self::getField($projectsElement, 'invoicedescription', $projectProjects))
                ->setRate(self::parseObjectAttribute(\PhpTwinfield\Rate::class, $projectProjects, $projectsElement, 'rate'))
                ->setValidFrom(self::parseDateAttribute(self::getField($projectsElement, 'validfrom', $projectProjects)))
                ->setValidTill(self::parseDateAttribute(self::getField($projectsElement, 'validtill', $projectProjects)));

            // Set the projects elements from the projects element attributes
            $projectProjects->setAuthoriserInherit(Util::parseBoolean(self::getAttribute($projectsElement, 'authoriser', 'inherit')))
                ->setAuthoriserLocked(Util::parseBoolean(self::getAttribute($projectsElement, 'authoriser', 'locked')))
                ->setBillableForRatio(Util::parseBoolean(self::getAttribute($projectsElement, 'billable', 'forratio')))
                ->setBillableInherit(Util::parseBoolean(self::getAttribute($projectsElement, 'billable', 'inherit')))
                ->setBillableLocked(Util::parseBoolean(self::getAttribute($projectsElement, 'billable', 'locked')))
                ->setCustomerInherit(Util::parseBoolean(self::getAttribute($projectsElement, 'customer', 'inherit')))
                ->setCustomerLocked(Util::parseBoolean(self::getAttribute($projectsElement, 'customer', 'locked')))
                ->setRateInherit(Util::parseBoolean(self::getAttribute($projectsElement, 'rate', 'inherit')))
                ->setRateLocked(Util::parseBoolean(self::getAttribute($projectsElement, 'rate', 'locked')));

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
                    $projectQuantity->setBillable(Util::parseBoolean(self::getField($quantityElement, 'billable', $projectQuantity)))
                        ->setLabel(self::getField($quantityElement, 'label', $projectQuantity))
                        ->setMandatory(Util::parseBoolean(self::getField($quantityElement, 'mandatory', $projectQuantity)))
                        ->setRate(self::parseObjectAttribute(\PhpTwinfield\Rate::class, $projectQuantity, $quantityElement, 'rate'));

                    // Set the quantity elements from the quantity element attributes
                    $projectQuantity->setBillableLocked(Util::parseBoolean(self::getAttribute($quantityElement, 'billable', 'locked')));

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
