<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Activity;
use PhpTwinfield\ActivityProjects;
use PhpTwinfield\ActivityQuantity;
use PhpTwinfield\Customer;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
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
        $activityElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $activity->setResult($activityElement->getAttribute('result'))
            ->setStatus($activityElement->getAttribute('status'));

        // Activity elements and their methods
        $activityTags = array(
            'behaviour'         => 'setBehaviour',
            'code'              => 'setCode',
            'inuse'             => 'setInUse',
            'name'              => 'setName',
            'office'            => 'setOffice',
            'shortname'         => 'setShortName',
            'touched'           => 'setTouched',
            'type'              => 'setType',
            'uid'               => 'setUID',
            'vatcode'           => 'setVatCode',
        );

        // Loop through all the tags
        foreach ($activityTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$activity, $method]);
        }

        $activityProjects = new ActivityProjects();
        $projectsElement = $responseDOM->getElementsByTagName('projects')->item(0);

        if ($projectsElement !== null) {
            // Make an ActivityProjects element and loop through custom tags
            $projectsTags = array(
                'authoriser'                => 'setAuthoriser',
                'billable'                  => 'setBillable',
                'invoicedescription'        => 'setInvoiceDescription',
                'rate'                      => 'setRate',
                'validfrom'                 => 'setValidFrom',
                'validtill'                 => 'setValidTill',
            );

            // Go through each projects element and add to the assigned method
            foreach ($projectsTags as $tag => $method) {
                $activityProjects->$method(self::getField($projectsElement, $tag));
            }

            $customercode = $responseDOM->getElementsByTagName('customer')->item(0);

            if (isset($customercode) && isset($customercode->textContent)) {
                $customer = new Customer();
                $customer->setCode($customercode->textContent);
                $activityProjects->setCustomer($customer);
            }

            $quantitiesDOMTag = $projectsElement->getElementsByTagName('quantities');

            if (isset($quantitiesDOMTag) && $quantitiesDOMTag->length > 0) {
                $quantitiesDOM = $quantitiesDOMTag->item(0);

                // Element tags and their methods for quantities
                $quantityTags = [
                    'billable'      => 'setBillable',
                    'label'         => 'setLabel',
                    'mandatory'     => 'setMandatory',
                    'rate'          => 'setRate',
                ];

                // Loop through each returned quantity for the project
                foreach ($quantitiesDOM->childNodes as $quantityDOM) {
                    if ($quantityDOM->nodeType !== 1) {
                        continue;
                    }

                    // Make a new tempory ActivityQuantity class
                    $activityQuantity = new ActivityQuantity();

                    // Loop through the element tags. Determine if it exists and set it if it does
                    foreach ($quantityTags as $tag => $method) {
                        // Get the dom element
                        $_tag = $quantityDOM->getElementsByTagName($tag)->item(0);

                        // Check if the tag is set, and its content is set, to prevent DOMNode errors
                        if (isset($_tag) && isset($_tag->textContent)) {
                            $activityQuantity->$method($_tag->textContent);
                        }
                    }

                    // Add the quantity to the project
                    $activityProjects->addQuantity($activityQuantity);

                    // Clean that memory!
                    unset ($activityQuantity);
                }
            }
        }

        // Set the custom class to the activity
        $activity->setProjects($activityProjects);

        return $activity;
    }
}
