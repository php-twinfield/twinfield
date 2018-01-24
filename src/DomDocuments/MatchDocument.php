<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\MatchLine;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

class MatchDocument extends BaseDocument
{
    public function addMatchSet(MatchSet $matchSet)
    {
        $set = $this->createElement("set");

        $this->appendOfficeField($set, $matchSet->getOffice());

        $set->appendChild($this->createNodeWithTextContent("matchcode", $matchSet->getMatchCode()->getValue()));

        $this->appendDateElement($set, "matchdate", $matchSet->getMatchDate());

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

        $element->appendChild($this->createNodeWithTextContent("transcode", $line->getTranscode()));
        $element->appendChild($this->createNodeWithTextContent("transnumber", $line->getTransnumber()));
        $element->appendChild($this->createNodeWithTextContent("transline", $line->getTransline()));

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