<?php

namespace PhpTwinfield\Mappers;

use Money\Currency;
use Money\Money;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\HasMessageInterface;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Office;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

abstract class BaseMapper
{
    protected static function checkForMessage(HasMessageInterface $object, \DOMElement $element): void
    {
        if ($element->hasAttribute('msg')) {
            $message = new Message();
            $message->setType($element->getAttribute('msgtype'));
            $message->setMessage($element->getAttribute('msg'));
            $message->setField($element->nodeName);

            $object->addMessage($message);
        }
    }

    protected static function getAttribute(\DOMElement $element, string $fieldTagName, string $attributeName): ?string
    {
        $fieldElement = $element->getElementsByTagName($fieldTagName)->item(0);

        if (!isset($fieldElement)) {
            return null;
        }

        if ($fieldElement->getAttribute($attributeName) === "") {
            return null;
        }

        return $fieldElement->getAttribute($attributeName);
    }

    protected static function getField(\DOMElement $element, string $fieldTagName, $object = null): ?string
    {
        $fieldElement = $element->getElementsByTagName($fieldTagName)->item(0);

        if (!isset($fieldElement)) {
            return null;
        }

        if (isset($object)) {
            self::checkForMessage($object, $fieldElement);
        }

        if ($fieldElement->textContent === "") {
            return null;
        }

        return $fieldElement->textContent;
    }
    
    protected static function getOfficeCurrencies(AuthenticatedConnection $connection, Office $office): array
    {
        $currencies = ["base" => '', "reporting" => ''];
        
        $officeApiConnector = new OfficeApiConnector($connection);
        $fullOffice = $officeApiConnector->get($office->getCode());
        
        if ($fullOffice->getResult() == 1) {
            $currencies['base'] = Util::objectToStr($fullOffice->getBaseCurrency());
            $currencies['reporting'] = Util::objectToStr($fullOffice->getReportingCurrency());
        }
        
        return $currencies;
    }

    protected static function parseDateAttribute(?string $value): ?\DateTimeImmutable
    {
        if (false !== strtotime($value)) {
            return Util::parseDate($value);
        }

        return null;
    }

    protected static function parseDateTimeAttribute(?string $value): ?\DateTimeImmutable
    {
        if (false !== strtotime($value)) {
            return Util::parseDateTime($value);
        }

        return null;
    }

    protected static function parseEnumAttribute(string $enumClass, ?string $value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return new $enumClass($value);
        } catch (\Exception $e) {
             return null;
        }
    }

    protected static function parseMoneyAttribute(?float $value, ?string $currency): ?Money
    {
        if ($value === null || $currency === null) {
            return null;
        }

        return Util::parseMoney($value, new Currency($currency));
    }

    protected static function parseUnknownEntity(HasMessageInterface $object, \DOMElement $element, string $fieldTagName): string
    {
        if (is_a($object, \PhpTwinfield\DimensionGroupDimension::class)) {
            $type = self::getField($element, "type", $object);
        } else {
            $type = self::getAttribute($element, $fieldTagName, "dimensiontype");
        }

        switch ($type) {
            case "ACT":
                return \PhpTwinfield\Activity::class;
            case "AST":
                return \PhpTwinfield\FixedAsset::class;
            case "BAS":
                return \PhpTwinfield\GeneralLedger::class;
            case "CRD":
                return \PhpTwinfield\Supplier::class;
            case "DEB":
                return \PhpTwinfield\Customer::class;
            case "KPL":
                return \PhpTwinfield\CostCenter::class;
            case "PNL":
                return \PhpTwinfield\GeneralLedger::class;
            case "PRJ":
                return \PhpTwinfield\Project::class;
            default:
                throw new \InvalidArgumentException("parseUnknownEntity function was unable to determine class name from \"{$type}\"");
        }
    }

    /**
     * Parse a code referring to a Twinfield Entity / PHP Twinfield object
     *
     * @param string|null $objectClass
     * @param HasMessageInterface $object
     * @param \DOMElement $element
     * @param string $fieldTagName
     * @param array $attributes
     * @return HasCodeInterface
     */
    protected static function parseObjectAttribute(?string $objectClass, HasMessageInterface $object, \DOMElement $element, string $fieldTagName, array $attributes = [])
    {
        $value = self::getField($element, $fieldTagName, $object);
        
        if ($value === null) {
            return null;
        }
        
        if ($objectClass === null) {
            $objectClass = self::parseUnknownEntity($object, $element, $fieldTagName);
        }
        
        $object2 = new $objectClass();
        $object2->setCode($value);

        foreach ($attributes as $attributeName => $method) {
            $object2->$method(self::getAttribute($element, $fieldTagName, $attributeName));
        }

        return $object2;
    }
}