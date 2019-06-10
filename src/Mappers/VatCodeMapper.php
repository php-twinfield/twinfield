<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\VatCode;
use PhpTwinfield\VatCodeAccount;
use PhpTwinfield\VatCodePercentage;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatCodeMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean VatCode entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return VatCode
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new VatCode object
        $vatCode = new VatCode();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/vat element
        $vatCodeElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $vatCode->setResult($vatCodeElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $vatCodeElement->getAttribute('status')));

        // Set the vat code elements from the vat element
        $vatCode->setCode(self::getField($vatCodeElement, 'code', $vatCode))
            ->setCreated(self::parseDateTimeAttribute(self::getField($vatCodeElement, 'created', $vatCode)))
            ->setModified(self::parseDateTimeAttribute(self::getField($vatCodeElement, 'modified', $vatCode)))
            ->setName(self::getField($vatCodeElement, 'name', $vatCode))
            ->setShortName(self::getField($vatCodeElement, 'shortname', $vatCode))
            ->setTouched(self::getField($vatCodeElement, 'touched', $vatCode))
            ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\VatType::class, self::getField($vatCodeElement, 'type', $vatCode)))
            ->setUID(self::getField($vatCodeElement, 'uid', $vatCode))
            ->setUser(self::parseObjectAttribute(\PhpTwinfield\User::class, $vatCode, $vatCodeElement, 'user', array('name' => 'setName', 'shortname' => 'setShortName')));

        // Get the percentages element
        $percentagesDOMTag = $responseDOM->getElementsByTagName('percentages');

        if (isset($percentagesDOMTag) && $percentagesDOMTag->length > 0) {
            // Loop through each returned percentage for the vatcode
            foreach ($percentagesDOMTag->item(0)->childNodes as $percentageElement) {
                if ($percentageElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary VatCodePercentage class
                $vatCodePercentage = new VatCodePercentage();

                 // Set the vat code percentage elements from the percentage element
                $vatCodePercentage->setCreated(self::parseDateTimeAttribute(self::getField($percentageElement, 'created', $vatCodePercentage)))
                    ->setDate(self::parseDateAttribute(self::getField($percentageElement, 'date', $vatCodePercentage)))
                    ->setName(self::getField($percentageElement, 'name', $vatCodePercentage))
                    ->setPercentage(self::getField($percentageElement, 'percentage', $vatCodePercentage))
                    ->setShortName(self::getField($percentageElement, 'shortname', $vatCodePercentage))
                    ->setUser(self::parseObjectAttribute(\PhpTwinfield\User::class, $vatCodePercentage, $percentageElement, 'user', array('name' => 'setName', 'shortname' => 'setShortName')));

                // Get the accounts element
                $accountsDOMTag = $percentageElement->getElementsByTagName('accounts');

                if (isset($accountsDOMTag) && $accountsDOMTag->length > 0) {
                    // Loop through each returned account for the percentage
                    foreach ($accountsDOMTag->item(0)->childNodes as $accountElement) {
                        if ($accountElement->nodeType !== 1) {
                            continue;
                        }

                        // Make a new temporary VatCodeAccount class
                        $vatCodeAccount = new VatCodeAccount();

                        // Set the ID attribute
                        $vatCodeAccount->setID($accountElement->getAttribute('id'));

                        // Set the vat code percentage account elements from the account element
                        $vatCodeAccount->setDim1(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $vatCodeAccount, $accountElement, 'dim1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setGroup(self::parseObjectAttribute(\PhpTwinfield\VatGroup::class, $vatCodeAccount, $accountElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
                            ->setGroupCountry(self::parseObjectAttribute(\PhpTwinfield\VatGroupCountry::class, $vatCodeAccount, $accountElement, 'groupcountry', array('name' => 'setName', 'shortname' => 'setShortName')))
                            ->setLineType(self::parseEnumAttribute(\PhpTwinfield\Enums\LineType::class, self::getField($accountElement, 'linetype', $vatCodeAccount)))
                            ->setPercentage(self::getField($accountElement, 'percentage', $vatCodeAccount));

                        // Add the account to the percentage
                        $vatCodePercentage->addAccount($vatCodeAccount);

                        // Clean that memory!
                        unset ($vatCodeAccount);
                    }
                }

                // Add the percentage to the vat code
                $vatCode->addPercentage($vatCodePercentage);

                // Clean that memory!
                unset ($vatCodePercentage);
            }
        }

        // Return the complete object
        return $vatCode;
    }
}