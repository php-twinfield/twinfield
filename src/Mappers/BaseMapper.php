<?php

namespace PhpTwinfield\Mappers;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Office;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

abstract class BaseMapper
{
    /**
     * @throws \PhpTwinfield\Exception
     */
    protected static function setFromTagValue(\DOMDocument $document, string $tag, callable $setter): void
    {
        $value = self::getValueFromTag($document, $tag);

        if ($value === null) {
            return;
        }

        if ($tag === "office") {
            \call_user_func($setter, Office::fromCode($value));
            return;
        }

        if ($tag === "date") {
            \call_user_func($setter, Util::parseDate($value));
            return;
        }

        if ($tag === "startvalue") {
            $currency = new Currency(self::getValueFromTag($document, "currency"));

            \call_user_func($setter, Util::parseMoney($value, $currency));

            return;
        }

        \call_user_func($setter, $value);
    }

    protected static function getValueFromTag(\DOMDocument $document, string $tag): ?string
    {
        /** @var \DOMNodeList $nodelist */
        $nodelist = $document->getElementsByTagName($tag);

        if ($nodelist->length === 0) {
            return null;
        }

        Assert::greaterThanEq($nodelist->length, 1);

        /** @var \DOMElement $element */
        $element = $nodelist[0];

        if ("" === $element->textContent) {
            return null;
        }

        return $element->textContent;
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

    protected static function getField($object, \DOMElement $element, string $fieldTagName): ?string
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

    private static function checkForMessage($object, \DOMElement $element): void
    {
        if ($element->hasAttribute('msg')) {
            $message = new Message();
            $message->setType($element->getAttribute('msgtype'));
            $message->setMessage($element->getAttribute('msg'));
            $message->setField($element->nodeName);

            $object->addMessage($message);
        }
    }

    protected static function parseBooleanAttribute(?string $value): ?bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    protected static function parseDateAttribute(?string $value): ?\DateTimeImmutable
    {
        if ((bool)strtotime($value)) {
            return Util::parseDate($value);
        } else {
            return null;
        }
    }

    protected static function parseDateTimeAttribute(?string $value): ?\DateTimeImmutable
    {
        if ((bool)strtotime($value)) {
            return Util::parseDateTime($value);
        } else {
            return null;
        }
    }

    protected static function parseEnumAttribute(string $enumName, ?string $value)
    {
        if ($value === null) {
            return null;
        }

        $enum = "\\PhpTwinfield\\Enums\\" . $enumName;

        try {
            $classReflex = new \ReflectionClass($enum);
            $classConstants = $classReflex->getConstants();

            foreach ($classConstants as $classConstant) {
                if ($value == $classConstant) {
                    return new $enum($value);
                }
            }
        } catch (\ReflectionException $e) {
            return null;
        }

        return null;
    }

    protected static function parseMoneyAttribute(?float $value): ?Money
    {
        if ($value === null) {
            return null;
        }

        return Util::parseMoney($value, new Currency('EUR'));
    }

    protected static function parseObjectAttribute(string $className, $object, \DOMElement $element, string $fieldTagName, array $attributes = null)
    {
        if ($className == "DimensionGroupDimension" || $className == "UnknownDimension") {
            if ($className == "DimensionGroupDimension") {
                $type = self::getField($object, $element, "type");
            } elseif ($className == "UnknownDimension") {
                $type = self::getAttribute($element, $fieldTagName, "dimensiontype");
            }

            switch ($type) {
                case "ACT":
                    $className = "Activity";
                    break;
                case "AST":
                    $className = "FixedAsset";
                    break;
                case "BAS":
                    $className = "GeneralLedger";
                    break;
                case "CRD":
                    $className = "Supplier";
                    break;
                case "DEB":
                    $className = "Customer";
                    break;
                case "KPL":
                    $className = "CostCenter";
                    break;
                case "PNL":
                    $className = "GeneralLedger";
                    break;
                case "PRJ":
                    $className = "Project";
                    break;
                default:
                    return null;
            }
        }

        $class = "\\PhpTwinfield\\" . $className;

        $object2 = new $class();
        $object2->setCode(self::getField($object, $element, $fieldTagName));

        if (isset($attributes)) {
            foreach ($attributes as $attributeName => $method) {
                $object2->$method(self::getAttribute($element, $fieldTagName, $attributeName));
            }
        }

        return $object2;
    }
}