<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\VatCode;
use PhpTwinfield\VatCodeAccount;
use PhpTwinfield\VatCodePercentage;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
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
        $vatcode = new VatCode();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $vatcodeElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $vatcode->setResult($vatcodeElement->getAttribute('result'));
        $vatcode->setStatus($vatcodeElement->getAttribute('status'));

        // VatCode elements and their methods
        $vatcodeTags = array(
            'code'              => 'setCode',
            'created'           => 'setCreated',
            'modified'          => 'setModified',
            'name'              => 'setName',
            'shortname'         => 'setShortName',
            'touched'           => 'setTouched',
            'type'              => 'setType',
            'uid'               => 'setUID',
            'user'              => 'setUser',
        );

        // Loop through all the tags
        foreach ($vatcodeTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$vatcode, $method]);
        }

        $percentagesDOMTag = $responseDOM->getElementsByTagName('percentages');

        if (isset($percentagesDOMTag) && $percentagesDOMTag->length > 0) {
            // Element tags and their methods for percentages
            $percentageTags = [
                'date'            => 'setDate',
                'name'            => 'setName',
                'percentage'      => 'setPercentage',
                'shortname'       => 'setShortName',
            ];

            $percentagesDOM = $percentagesDOMTag->item(0);

            // Loop through each returned percentage for the vatcode
            foreach ($percentagesDOM->childNodes as $percentageDOM) {
                // Make a new tempory VatCodePercentage class
                $vatcodePercentage = new VatCodePercentage();

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($percentageTags as $tag => $method) {
                    // Get the dom element
                    $_tag = $percentageDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $vatcodePercentage->$method($_tag->textContent);
                    }
                }

                $accountsDOMTag = $percentageDOM->getElementsByTagName('accounts');

                if (isset($accountsDOMTag) && $accountsDOMTag->length > 0) {
                    // Element tags and their methods for accounts
                    $accountTags = [
                        'dim1'              => 'setDim1',
                        'group'             => 'setGroup',
                        'groupcountry'      => 'setGroupCountry',
                        'linetype'          => 'setLineType',
                        'percentage'        => 'setPercentage',
                    ];

                    $accountsDOM = $accountsDOMTag->item(0);

                    // Loop through each returned account for the percentage
                    foreach ($accountsDOM->childNodes as $accountDOM) {
                        // Make a new tempory VatCodeAccount class
                        $vatcodeAccount = new VatCodeAccount();
                        $vatcodeAccount->setID($accountDOM->getAttribute('id'));

                        // Loop through the element tags. Determine if it exists and set it if it does
                        foreach ($accountTags as $tag => $method) {
                            // Get the dom element
                            $_tag = $accountDOM->getElementsByTagName($tag)->item(0);

                            // Check if the tag is set, and its content is set, to prevent DOMNode errors
                            if (isset($_tag) && isset($_tag->textContent)) {
                                $vatcodeAccount->$method($_tag->textContent);
                            }
                        }

                        // Add the account to the percentage
                        $vatcodePercentage->addAccount($vatcodeAccount);

                        // Clean that memory!
                        unset ($vatcodeAccount);
                    }
                }

                // Add the percentage to the vat code
                $vatcode->addPercentage($vatcodePercentage);

                // Clean that memory!
                unset ($vatcodePercentage);
            }
        }
        
        return $vatcode;
    }
}
