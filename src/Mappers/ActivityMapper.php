<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Activity;
use PhpTwinfield\ActivityProjects;
use PhpTwinfield\ActivityQuantity;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

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
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $activityElement->getAttribute('status')));

        // Set the activity elements from the activity element
        $activity->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($activityElement, 'behaviour', $activity)))
            ->setCode(self::getField($activityElement, 'code', $activity))
            ->setInUse(Util::parseBoolean(self::getField($activityElement, 'name', $activity)))
            ->setName(self::getField($activityElement, 'name', $activity))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $activity, $activityElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($activityElement, 'shortname', $activity))
            ->setTouched(self::getField($activityElement, 'touched', $activity))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $activity, $activityElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($activityElement, 'uid', $activity))
            ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $activity, $activityElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

        // Get the projects element
        $projectsElement = $responseDOM->getElementsByTagName('projects')->item(0);

        if ($projectsElement !== null) {
            // Make a new temporary ActivityProjects class
            $activityProjects = new ActivityProjects();

            // Set the projects elements from the projects element
            $activityProjects->setAuthoriser(self::parseObjectAttribute(\PhpTwinfield\User::class, $activityProjects, $projectsElement, 'authoriser'))
                ->setBillable(Util::parseBoolean(self::getField($projectsElement, 'billable', $activityProjects)))
                ->setCustomer(self::parseObjectAttribute(\PhpTwinfield\Customer::class, $activityProjects, $projectsElement, 'customer'))
                ->setInvoiceDescription(self::getField($projectsElement, 'invoicedescription', $activityProjects))
                ->setRate(self::parseObjectAttribute(\PhpTwinfield\Rate::class, $activityProjects, $projectsElement, 'rate'))
                ->setValidFrom(self::parseDateAttribute(self::getField($projectsElement, 'validfrom', $activityProjects)))
                ->setValidTill(self::parseDateAttribute(self::getField($projectsElement, 'validtill', $activityProjects)));

            // Set the projects elements from the projects element attributes
            $activityProjects->setAuthoriserInherit(Util::parseBoolean(self::getAttribute($projectsElement, 'authoriser', 'inherit')))
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

                    // Make a new temporary ActivityQuantity class
                    $activityQuantity = new ActivityQuantity();

                    // Set the quantity elements from the quantity element
                    $activityQuantity->setBillable(Util::parseBoolean(self::getField($quantityElement, 'billable', $activityQuantity)))
                        ->setLabel(self::getField($quantityElement, 'label', $activityQuantity))
                        ->setMandatory(Util::parseBoolean(self::getField($quantityElement, 'mandatory', $activityQuantity)))
                        ->setRate(self::parseObjectAttribute(\PhpTwinfield\Rate::class, $activityQuantity, $quantityElement, 'rate'));

                    // Set the quantity elements from the quantity element attributes
                    $activityQuantity->setBillableLocked(Util::parseBoolean(self::getAttribute($quantityElement, 'billable', 'locked')));

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
