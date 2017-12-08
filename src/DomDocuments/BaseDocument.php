<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Enums\PerformanceType;
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
        $attr->value = $value ? "true" : "false";

        return $attr;
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
        $element->appendChild($this->createElement("currency", $object->getCurrency()));
        $element->appendChild($this->createElement("startvalue", Util::formatMoney($object->getStartvalue())));
        $element->appendChild($this->createElement("closevalue", Util::formatMoney($object->getClosevalue())));
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
            $element->appendChild($this->createElement("freetext1", $object->getFreetext1()));
        }
        if ($object->getFreetext1() !== null) {
            $element->appendChild($this->createElement("freetext2", $object->getFreetext2()));
        }
        if ($object->getFreetext1() !== null) {
            $element->appendChild($this->createElement("freetext3", $object->getFreetext3()));
        }
    }

    protected function appendValueValues(\DOMElement $element, $object): void
    {
        Assert::true(Util::objectUses(ValueFields::class, $object));

        /** @var ValueFields $object */
        $element->appendChild($this->createElement("debitcredit", $object->getDebitCredit()));
        $element->appendChild($this->createElement("value", Util::formatMoney($object->getValue())));
    }

    protected function appendPerformanceTypeFields(\DOMElement $element, $object): void
    {
        Assert::true(Util::objectUses(PerformanceFields::class, $object));

        /** @var PerformanceFields $object */
        $element->appendChild($this->createElement("performancetype", $object->getPerformanceType()));
        $element->appendChild($this->createElement("performancecountry", $object->getPerformanceCountry()));
        $element->appendChild($this->createElement("performancevatnumber", $object->getPerformanceVatNumber()));

        if ($object->getPerformanceType() == PerformanceType::SERVICES()) {
            $element->appendChild($this->createElement("performancedate", $object->getPerformanceDate()));
        }
    }
}