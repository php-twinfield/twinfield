<?php
namespace PhpTwinfield\Mappers;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Enums\MatchCode;
use PhpTwinfield\MatchLine;
use PhpTwinfield\MatchReference;
use PhpTwinfield\MatchReferenceInterface;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Office;
use PhpTwinfield\Util;

class MatchSetMapper extends BaseMapper
{
    /**
     * TODO: This does not contain all fields from the MatchLine
     *
     * @return MatchSet
     */
    public static function map(\DOMDocument $document): MatchSet
    {
        $matchSet = self::createMatchSetFrom($document);

        self::addLines($document, $matchSet);

        return $matchSet;
    }

    private static function createMatchSetFrom(\DOMDocument $document): MatchSet
    {
        $matchSet = new MatchSet();

        $matchSet->setOffice(Office::fromCode(self::getValueFromTag($document, "office")));
        $matchSet->setMatchCode(new MatchCode(self::getValueFromTag($document, "matchcode")));
        $matchSet->setMatchDate(
            \DateTimeImmutable::createFromFormat("Ymd", self::getValueFromTag($document, "matchdate"))
        );

        return $matchSet;
    }

    private static function addLines(\DOMDocument $document, MatchSet $matchSet): void
    {
        $element = $document->documentElement;

        /**  @var \DOMElement $lineElement */
        foreach ($element->getElementsByTagName("line") as $lineElement) {
            MatchLine::addToMatchSet(
                $matchSet,
                self::getMatchReferenceFrom($lineElement, $matchSet->getOffice()),
                self::getMatchValueFrom($lineElement)
            );
        }
    }

    private static function getMatchReferenceFrom(
        \DOMElement $lineElement,
        Office $office
    ): MatchReferenceInterface {
        return new MatchReference(
            $office,
            self::getField($lineElement, 'transcode'),
            self::getField($lineElement, 'transnumber'),
            self::getField($lineElement, 'transline')
        );
    }

    private static function getMatchValueFrom(\DOMElement $lineElement): ?Money
    {
        $matchValue = self::getField($lineElement, 'matchvalue');

        if ($matchValue === null) {
            return null;
        }

        // TODO: Perhaps not hard code this to EUR, but the element doesn't seem to contain a currency
        return Util::parseMoney($matchValue, new Currency('EUR'));
    }
}
