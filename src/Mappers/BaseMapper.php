<?php

namespace PhpTwinfield\Mappers;

use Money\Currency;
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

    protected static function getField(\DOMElement $element, string $fieldTagName): ?string
    {
        $fieldElement = $element->getElementsByTagName($fieldTagName)->item(0);
        if (!isset($fieldElement)) {
            return null;
        }

        return $fieldElement->textContent;
    }
}