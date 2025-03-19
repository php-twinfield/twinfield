<?php

namespace PhpTwinfield\Mappers;

use Money\Currency;
use PhpTwinfield\BaseObject;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Office;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

abstract class BaseMapper
{
    /**
     * @param \DOMDocument $document
     * @param string $tag
     * @param callable $setter
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

    /**
     * @param \DOMDocument $document
     * @param string $tag
     * @return null|string
     */
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

    /**
     * @param \DOMElement $element
     * @param string $fieldTagName
     * @return null|string
     */
    protected static function getField(\DOMElement $element, string $fieldTagName): ?string
    {
        $fieldElement = $element->getElementsByTagName($fieldTagName)->item(0);
        if (!isset($fieldElement)) {
            return null;
        }

        if ($fieldElement->textContent === "") {
            return null;
        }

        return $fieldElement->textContent;
    }

    /**
     * @param \DOMElement $element
     * @param string $tag
     * @param callable $setter
     * @throws \PhpTwinfield\Exception
     */
    protected static function setValueFromElementTag(\DOMElement $element, string $tag, callable $setter)
    {
        $value = self::getField($element, $tag);

        if ($value === null) {
            return;
        }

        if ($tag === "validfrom") {
            \call_user_func($setter, Util::parseDate($value));
            return;
        }

        if ($tag === "validto") {
            \call_user_func($setter, Util::parseDate($value));
            return;
        }

        \call_user_func($setter, $value);
    }

    /**
     * @param BaseObject $object
     * @param \DOMElement $element
     */
    protected static function checkForMessage(BaseObject $object, \DOMElement $element): void
    {
        if ($element->hasAttribute('msg')) {
            $message = new Message;
            $message->setType($element->getAttribute('msgtype'));
            $message->setMessage($element->getAttribute('msg'));
            $message->setField($element->nodeName);

            $object->addMessage($message);
        }
    }
}
