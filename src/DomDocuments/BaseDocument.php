<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

/**
 * You should add a public function add<Type>($instance): void method which will add an instance to the rootElement, so
 * that you can send multiple elements in one go.
 */
abstract class BaseDocument extends \DOMDocument
{
    /**
     * @var \DOMElement
     */
    protected $rootElement;

    /**
     * The root tag, e.g.
     *
     * @return string
     */
    abstract protected function getRootTagName(): string;

    public function __construct($version = "1.0", $encoding = "UTF-8")
    {
        parent::__construct($version, $encoding);

        $this->rootElement = $this->createElement($this->getRootTagName());
        $this->appendChild($this->rootElement);
    }

    protected function createBooleanAttribute(string $name, bool $value): \DOMAttr
    {
        $attr = $this->createAttribute($name);
        $attr->value = Util::formatBoolean($value);

        return $attr;
    }

    protected function appendDateElement(\DOMElement $element, string $name, \DateTimeInterface $date): void
    {
        $element->appendChild(
            $this->createNodeWithTextContent($name, Util::formatDate($date))
        );
    }

    protected function appendOfficeField(\DOMElement $element, Office $office): void
    {
        $element->appendChild(
            $this->createNodeWithTextContent("office", $office->getCode())
        );
    }

    /**
     * Utility method to get a formatted value using a callable.
     *
     * @param callable $callback
     * @return null|string
     */
    protected function getValueFromCallback(callable $callback): ?string
    {
        $value = call_user_func($callback);

        if ($value instanceof \DateTimeInterface) {
            $value = Util::formatDate($value);
        }

        if (is_bool($value)) {
            $value = Util::formatBoolean($value);
        }

        return $value;
    }

    /**
     * Adds currency, startvalue and closevalue to $element.
     *
     * @param \DOMElement $element
     * @param $object
     */
    protected function appendStartCloseValues(\DOMElement $element, $object): void
    {
        Assert::true(Util::objectUses(StartAndCloseValueFields::class, $object));

        /** @var StartAndCloseValueFields $object */
        $element->appendChild($this->createNodeWithTextContent("currency", $object->getCurrency()));
        $element->appendChild($this->createNodeWithTextContent("startvalue", Util::formatMoney($object->getStartvalue())));
        $element->appendChild($this->createNodeWithTextContent("closevalue", Util::formatMoney($object->getClosevalue())));
    }

    /**
     * Adds freetext[1-3] to the $element, unless they are null.
     *
     * @param \DOMElement $element
     * @param $object
     */
    protected function appendFreeTextFields(\DOMElement $element, $object): void
    {
        Assert::true(Util::objectUses(FreeTextFields::class, $object));

        /** @var FreeTextFields $object */
        if ($object->getFreetext1() !== null) {
            $element->appendChild($this->createNodeWithTextContent("freetext1", $object->getFreetext1()));
        }
        if ($object->getFreetext1() !== null) {
            $element->appendChild($this->createNodeWithTextContent("freetext2", $object->getFreetext2()));
        }
        if ($object->getFreetext1() !== null) {
            $element->appendChild($this->createNodeWithTextContent("freetext3", $object->getFreetext3()));
        }
    }

    protected function appendValueValues(\DOMElement $element, $object): void
    {
        Assert::true(Util::objectUses(ValueFields::class, $object));

        /** @var ValueFields $object */
        $element->appendChild($this->createNodeWithTextContent("debitcredit", $object->getDebitCredit()));
        $element->appendChild($this->createNodeWithTextContent("value", Util::formatMoney($object->getValue())));
    }

    protected function appendPerformanceTypeFields(\DOMElement $element, $object): void
    {
        /** @var PerformanceFields $object */

        Assert::true(Util::objectUses(PerformanceFields::class, $object));

        if ($object->getPerformanceType() !== null) {
            $element->appendChild($this->createNodeWithTextContent("performancetype", $object->getPerformanceType()));
        }

        if ($object->getPerformanceCountry() !== null) {
            $element->appendChild($this->createNodeWithTextContent("performancecountry", $object->getPerformanceCountry()));
        }

        if ($object->getPerformanceVatNumber() !== null) {
            $element->appendChild($this->createNodeWithTextContent("performancevatnumber", $object->getPerformanceVatNumber()));
        }

        if ($object->getPerformanceDate() != null && $object->getPerformanceType()->equals(PerformanceType::SERVICES())) {
            $this->appendDateElement($element, "performancedate", $object->getPerformanceDate());
        }
    }

    /**
     * Create an element and set some value as its innerText.
     *
     * Use this instead of createElement().
     *
     * @param string $tag
     * @param string $textContent
     * @return \DOMElement
     */
    final protected function createNodeWithTextContent(string $tag, string $textContent): \DOMElement
    {
        $element = $this->createElement($tag);
        $element->textContent = $textContent;

        return $element;
    }
}