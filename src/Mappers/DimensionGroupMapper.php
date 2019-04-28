<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\DimensionGroup;
use PhpTwinfield\DimensionGroupDimension;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class DimensionGroupMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean DimensionGroup entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return DimensionGroup
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new DimensionGroup object
        $dimensiongroup = new DimensionGroup();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $dimensiongroupElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $dimensiongroup->setResult($dimensiongroupElement->getAttribute('result'));
        $dimensiongroup->setStatus($dimensiongroupElement->getAttribute('status'));

        // DimensionGroup elements and their methods
        $dimensiongroupTags = array(
            'code'                          => 'setCode',
            'name'                          => 'setName',
            'office'                        => 'setOffice',
            'shortname'                     => 'setShortName',
        );

        // Loop through all the tags
        foreach ($dimensiongroupTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$dimensiongroup, $method]);
        }

        $dimensionsDOMTag = $responseDOM->getElementsByTagName('dimensions');

        if (isset($dimensionsDOMTag) && $dimensionsDOMTag->length > 0) {
            // Element tags and their methods for dimensions
            $dimensionTags = [
                'code'      => 'setCode',
                'type'      => 'setType',
            ];

            $dimensionsDOM = $dimensionsDOMTag->item(0);

            // Loop through each returned dimension for the dimensiongroup
            foreach ($dimensionsDOM->childNodes as $dimensionDOM) {

                // Make a new tempory DimensionGroupDimension class
                $dimensionGroupDimension = new DimensionGroupDimension();

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($dimensionTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $dimensionsDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $dimensionGroupDimension->$method($_tag->textContent);
                    }
                }

                // Add the dimension to the dimension group
                $dimensiongroup->addDimension($dimensionGroupDimension);

                // Clean that memory!
                unset ($dimensionGroupDimension);
            }
        }

        return $dimensiongroup;
    }
}
