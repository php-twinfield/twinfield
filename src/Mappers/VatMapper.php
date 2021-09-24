<?php

declare(strict_types=1);

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Account;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Response\Response;
use PhpTwinfield\VatCodeDetail;
use PhpTwinfield\VatCodePercentage;

final class VatMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Vat entity.
     *
     * @access public
     * @param \PhpTwinfield\Response\Response $response
     * @return VatCodeDetail
     */
    public static function map(Response $response): VatCodeDetail
    {
        $vatCodeDetail = new VatCodeDetail();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Set the status attribute
        $vatElement = $responseDOM->getElementsByTagName('vat')->item(0);
        $vatCodeDetail->setStatus($vatElement->getAttribute('status'));

        // Vat elements and their methods
        $vatTags = [
            'code' => 'setCode',
            'name' => 'setName',
            'shortname' => 'setShortName',
            'uid' => 'setUID',
            'created' => 'setCreatedFromString',
            'modified' => 'setModifiedFromString',
            'touched' => 'setTouched',
            'type' => 'setType',
            'user' => 'setUser',
        ];

        // Loop through all the tags
        foreach ($vatTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$vatCodeDetail, $method]);
        }

        $xpath = new \DOMXPath($responseDOM);
        $percentages = [];
        foreach ($xpath->query('/vat/percentages/percentage', $vatElement) as $percentageNode) {
            $percentages[] = $percentage = new VatCodePercentage();

            $percentage
                ->setStatus($percentageNode->getAttribute('status'))
                ->setInUse($percentageNode->getAttribute('inuse') === 'true')
                ->setDateFromString(self::getField($percentageNode, 'date'))
                ->setPercentage((float) self::getField($percentageNode, 'percentage'))
                ->setCreatedFromString(self::getField($percentageNode, 'created'))
                ->setName(self::getField($percentageNode, 'name'))
                ->setShortname(self::getField($percentageNode, 'shortname'))
                ->setUser(self::getField($percentageNode, 'user'));

            $accounts = [];
            foreach ($xpath->query('accounts/account', $percentageNode) as $accountNode) {
                $accounts[] = $account = new Account();

                $account
                    ->setId($accountNode->getAttribute('id'))
                    ->setDim1(self::getField($accountNode, 'dim1'))
                    ->setGroupCountry(self::getField($accountNode, 'groupcountry'))
                    ->setGroup(self::getField($accountNode, 'group'))
                    ->setPercentage((float) self::getField($accountNode, 'percentage'))
                    ->setLineType(new LineType(self::getField($accountNode, 'linetype')));
            }

            $percentage->setAccounts($accounts);
        }
        $vatCodeDetail->setPercentages($percentages);

        return $vatCodeDetail;
    }
}
