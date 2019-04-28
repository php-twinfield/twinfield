<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\DimensionType;
use PhpTwinfield\DimensionTypeAddress;
use PhpTwinfield\DimensionTypeLevels;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class DimensionTypeMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean DimensionType entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return DimensionType
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new DimensionType object
        $dimensiontype = new DimensionType();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $dimensiontypeElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $dimensiontype->setResult($dimensiontypeElement->getAttribute('result'));
        $dimensiontype->setStatus($dimensiontypeElement->getAttribute('status'));

        // DimensionType elements and their methods
        $dimensiontypeTags = array(
            'code'                          => 'setCode',
            'mask'                          => 'setMask',
            'name'                          => 'setName',
            'office'                        => 'setOffice',
            'shortname'                     => 'setShortName',
        );

        // Loop through all the tags
        foreach ($dimensiontypeTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$dimensiontype, $method]);
        }

        // Make an DimensionTypeBalanceAccount element and loop through custom tags
        $levelsTags = array(
            'financials'        => 'setFinancials',
            'fixedassets'       => 'setFixedAssets',
            'invoices'          => 'setInvoices',
            'time'              => 'setTime',
        );

        $dimensionTypeLevels = new DimensionTypeLevels();

        $levelsElement = $responseDOM->getElementsByTagName('levels')->item(0);

        if ($levelsElement !== null) {
            // Go through each levels element and add to the assigned method
            foreach ($levelsTags as $tag => $method) {
                $dimensionTypeLevels->$method(self::getField($levelsElement, $tag));
            }
        }

        // Set the custom class to the dimensiontype
        $dimensiontype->setLevels($dimensionTypeLevels);

        // Make an DimensionTypeAddressAccount element and loop through custom tags
        $addressTags = array(
            'label1'        => 'setLabel1',
            'label2'        => 'setLabel2',
            'label3'        => 'setLabel3',
            'label4'        => 'setLabel4',
            'label5'        => 'setLabel5',
            'label6'        => 'setLabel6',
        );

        $dimensionTypeAddress = new DimensionTypeAddress();

        $addressElement = $responseDOM->getElementsByTagName('address')->item(0);

        if ($addressElement !== null) {
            // Go through each addressaccounts element and add to the assigned method
            foreach ($addressTags as $tag => $method) {
                $dimensionTypeAddress->$method(self::getField($addressElement, $tag));
            }
        }

        // Set the custom class to the dimensiontype
        $dimensiontype->setAddress($dimensionTypeAddress);

        return $dimensiontype;
    }
}
