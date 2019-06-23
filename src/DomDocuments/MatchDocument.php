<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\MatchLine;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Util;

class MatchDocument extends BaseDocument
{
    public function addMatchSet(MatchSet $matchSet)
    {
        $set = $this->createElement("set");

        $set->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($matchSet->getOffice())));

        $set->appendChild($this->createNodeWithTextContent("matchcode", $matchSet->getMatchCode()->getValue()));

        $set->appendChild($this->createNodeWithTextContent("matchdate", Util::formatDate($matchSet->getMatchDate())));

        $lines = $this->createElement("lines");

        foreach ($matchSet->getLines() as $line) {
            $lines->appendChild($this->createLineElement($line));
        }

        $set->appendChild($lines);

        $this->rootElement->appendChild($set);
    }

    private function createLineElement(MatchLine $line): \DOMElement
    {
        $element = $this->createElement("line");

        $element->appendChild($this->createNodeWithTextContent("transcode", $line->getTransCode()));
        $element->appendChild($this->createNodeWithTextContent("transnumber", $line->getTransNumber()));
        $element->appendChild($this->createNodeWithTextContent("transline", $line->getTransLine()));

        if ($line->getMatchValue() !== null) {
            $element->appendChild($this->createNodeWithTextContent("matchvalue", Util::formatMoney($line->getMatchValue())));
        }

        if ($line->getWriteOff() !== null) {
            $writeoff = $this->createNodeWithTextContent("writeoff", Util::formatMoney($line->getWriteOff()));

            $attribute = $this->createAttribute("type");
            $attribute->value = $line->getWriteOffType();

            $writeoff->appendChild($attribute);

            $element->appendChild($writeoff);
        }

        return $element;
    }

    protected function getRootTagName(): string
    {
        return "match";
    }
}