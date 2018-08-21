<?php

namespace PhpTwinfield;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Webmozart\Assert\Assert;

final class Util
{
    public static function formatMoney(Money $money): string
    {
        $decimalformatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $decimalformatter->format($money);
    }

    public static function parseMoney(string $moneyString, Currency $currency): Money
    {
        $parser = new DecimalMoneyParser(new ISOCurrencies());
        return $parser->parse($moneyString, $currency->getCode());
    }

    /**
     * Format a date according to Twinfield specifications.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    public static function formatDate(\DateTimeInterface $date): string
    {
        return $date->format("Ymd");
    }

    /**
     * Parse a date string from a Twinfield XML.
     *
     * @param string $dateString
     * @return \DateTimeImmutable
     * @throws Exception
     */
    public static function parseDate(string $dateString): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat("Ymd|", $dateString);

        if (false === $date) {
            throw new Exception("Invalid date, expected in yyyymmdd format, got \"{$dateString}\".");
        }

        return $date;
    }

    /**
     * Parse a date time string from a Twinfield XML.
     *
     * @param string $dateString
     * @return \DateTimeImmutable
     * @throws Exception
     */
    public static function parseDateTime(string $dateString)
    {
        $date = \DateTimeImmutable::createFromFormat("YmdHis", $dateString);

        if (false === $date) {
            throw new Exception("Invalid date, expected in yyyymmddhhiiss format, got \"{$dateString}\".");
        }

        return $date;
    }

    /**
     * @param bool $boolean
     * @return string
     */
    public static function formatBoolean(bool $boolean): string
    {
        return $boolean ? "true" : "false";
    }

    /**
     * @param string $input
     * @return bool
     * @throws Exception
     */
    public static function parseBoolean(string $input): bool
    {
        switch ($input) {
            case "true":
                return true;
            case "false":
                return false;
        }

        throw new Exception("Unknown boolean value \"{$input}\".");
    }

    /**
     * Get all the traits an object uses, includes traits from all parent classes.
     *
     * @param string $trait
     * @param object $object
     * @return bool
     */
    public static function objectUses(string $trait, $object): bool
    {
        Assert::object($object);

        $traits = array_reduce(
            class_parents($object),
            function (array $carry, string $item) {
                return array_merge($carry, class_uses($item));
            },
            class_uses(get_class($object))
        );

        return in_array($trait, $traits);
    }

    public static function getPrettyXml(\DOMDocument $document): string
    {
        $oldPreserveWhiteSpace = $document->preserveWhiteSpace;
        $oldFormatOutput = $document->formatOutput;

        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        $xml_string = $document->saveXML();

        $document->preserveWhiteSpace = $oldPreserveWhiteSpace;
        $document->formatOutput = $oldFormatOutput;

        return $xml_string ?: '';
    }
}