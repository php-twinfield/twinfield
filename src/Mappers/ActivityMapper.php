<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Activity;
use PhpTwinfield\ActivityProjects;
use PhpTwinfield\ActivityQuantity;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ActivityMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Activity entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Activity
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Activity object
        $activity = new Activity();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/activity element
        $activityElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $activity->setResult($activityElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $activityElement->getAttribute('status')));

        // Set the activity elements from the activity element
        $activity->setBehaviour(self::parseEnumAttribute('Behaviour', self::getField($activity, $activityElement, 'behaviour')))
            ->setCode(self::getField($activity, $activityElement, 'code'))
            ->setInUse(self::parseBooleanAttribute(self::getField($activity, $activityElement, 'name')))
            ->setName(self::getField($activity, $activityElement, 'name'))
            ->setOffice(self::parseObjectAttribute('Office', $activity, $activityElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($activity, $activityElement, 'shortname'))
            ->setTouched(self::getField($activity, $activityElement, 'touched'))
            ->setType(self::parseObjectAttribute('DimensionType', $activity, $activityElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($activity, $activityElement, 'uid'))
            ->setVatCode(self::parseObjectAttribute('VatCode', $activity, $activityElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

        // Get the projects element
        $projectsElement = $responseDOM->getElementsByTagName('projects')->item(0);

        if ($projectsElement !== null) {
            // Make a new temporary ActivityProjects class
            $activityProjects = new ActivityProjects();

            // Set the projects elements from the projects element
            $activityProjects->setAuthoriser(self::parseObjectAttribute('User', $activityProjects, $projectsElement, 'authoriser'))
                ->setBillable(self::parseBooleanAttribute(self::getField($activityProjects, $projectsElement, 'billable')))
                ->setCustomer(self::parseObjectAttribute('Customer', $activityProjects, $projectsElement, 'customer'))
                ->setInvoiceDescription(self::getField($activityProjects, $projectsElement, 'invoicedescription'))
                ->setRate(self::parseObjectAttribute('Rate', $activityProjects, $projectsElement, 'rate'))
                ->setValidFrom(self::parseDateAttribute(self::getField($activityProjects, $projectsElement, 'validfrom')))
                ->setValidTill(self::parseDateAttribute(self::getField($activityProjects, $projectsElement, 'validtill')));

            // Set the projects elements from the projects element attributes
            $activityProjects->setAuthoriserInherit(self::parseBooleanAttribute(self::getAttribute($projectsElement, 'authoriser', 'inherit')))
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

                    // Make a new temporary ActivityQuantity class
                    $activityQuantity = new ActivityQuantity();

                    // Set the quantity elements from the quantity element
                    $activityQuantity->setBillable(self::parseBooleanAttribute(self::getField($activityQuantity, $quantityElement, 'billable')))
                        ->setLabel(self::getField($activityQuantity, $quantityElement, 'label'))
                        ->setMandatory(self::parseBooleanAttribute(self::getField($activityQuantity, $quantityElement, 'mandatory')))
                        ->setRate(self::parseObjectAttribute('Rate', $activityQuantity, $quantityElement, 'rate'));

                    // Set the quantity elements from the quantity element attributes
                    $activityQuantity->setBillableLocked(self::parseBooleanAttribute(self::getAttribute($quantityElement, 'billable', 'locked')));

                    // Add the quantity to the project
                    $activityProjects->addQuantity($activityQuantity);

                    // Clean that memory!
                    unset ($activityQuantity);
                }
            }

            // Set the custom class to the project
            $activity->setProjects($activityProjects);
        }

        // Return the complete object
        return $activity;
    }
}