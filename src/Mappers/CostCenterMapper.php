<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\CostCenter;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class CostCenterMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean CostCenter entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return CostCenter
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new CostCenter object
        $costCenter = new CostCenter();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $costCenterElement = $responseDOM->documentElement;

        // Set the status and result attribute
        $costCenter->setStatus($costCenterElement->getAttribute('status'));
        $costCenter->setResult($costCenterElement->getAttribute('result'));

        // CostCenter elements and their methods
        $costCenterTags = array(
            'behaviour'         => 'setBehaviour',
            'code'              => 'setCode',
            'inuse'             => 'setInUse',
            'name'              => 'setName',
            'office'            => 'setOffice',
            'touched'           => 'setTouched',
            'type'              => 'setType',
            'uid'               => 'setUID',
        );

        // Loop through all the tags
        foreach ($costCenterTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$costCenter, $method]);
        }

        return $costCenter;
    }
}
