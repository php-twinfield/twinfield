<?php

namespace Pronamic\Twinfield\PurchaseInvoice\Mapper;

use Pronamic\Twinfield\Currency;
use Pronamic\Twinfield\PurchaseInvoice\PurchaseInvoice;
use Pronamic\Twinfield\User\User;

/**
 * Class PurchaseInvoiceMapper
 *
 * Maps XML-formatted response to PurchaseInvoice objects.
 *
 * @package Pronamic\Twinfield\PurchaseInvoice\Mapper
 * @author Emile Bons <emile@emilebons.nl>
 */
class PurchaseInvoiceMapper
{

    public static function map($response)
    {
        $responseDOM = new \DOMDocument();
        $responseDOM->loadXML($response);
        $purchaseInvoiceTags = array(
            'date' => 'setDate',
            'duedate' => 'setDueDate',
            'inputdate' => 'setInputDate',
            'invoicenumber' => 'setInvoiceNumber',
            'modificationdate' => 'setModificationDate',
            'number' => 'setNumber',
            'origin' => 'setOrigin',
            'originreference' => 'setOriginReference',
            'period' => 'setPeriod',
            'regime' => 'setRegime',
        );

        $purchaseInvoice = new PurchaseInvoice();

        foreach ($purchaseInvoiceTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);
            if(isset($_tag) && isset($_tag->textContent)) {
                $purchaseInvoice->$method($_tag->textContent);
            }
        }

        $currencyTag = $responseDOM->getElementsByTagName('currency')->item(0);
        if(isset($currencyTag)) {
            $currency = new Currency();
            $currency->setCode($currencyTag->textContent);
            $currency->setName($currencyTag->attributes->getNamedItem('name')->textContent);
            $currency->setShortName($currencyTag->attributes->getNamedItem('shortname')->textContent);
            $purchaseInvoice->setCurrency($currency);
        }

        $userTag = $responseDOM->getElementsByTagName('user')->item(0);
        if(isset($userTag)) {
            $user = new User();
            $user->setCode($userTag->textContent);
            $user->setName($userTag->attributes->getNamedItem('name')->textContent);
            $user->setShortName($userTag->attributes->getNamedItem('shortname')->textContent);
            $purchaseInvoice->setUser($user);
        }

        return $purchaseInvoice;
    }
}