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
    /**
     * Format a money object to string according to Twinfield specifications.
     *
     * @param Money|null $money
     * @return string|null
     */
    public static function formatMoney(?Money $money): ?string
    {
        if ($money === null) {
            return null;
        }

        $decimalformatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $decimalformatter->format($money);
    }

    /**
     * Parse a money string and Currency object.
     *
     * @param string|null $moneyString
     * @param Currency|null $currency
     * @return Money|null
     */
    public static function parseMoney(?string $moneyString, ?Currency $currency): ?Money
    {
        if ($moneyString === null || $currency === null) {
            return null;
        }

        $parser = new DecimalMoneyParser(new ISOCurrencies());
        return $parser->parse($moneyString, $currency);
    }

    /**
     * Format a date according to Twinfield specifications.
     *
     * @param \DateTimeInterface|null $date
     * @return string|null
     */
    public static function formatDate(?\DateTimeInterface $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return $date->format("Ymd");
    }

    /**
     * Parse a date string from a Twinfield XML.
     *
     * @param string|null $dateString
     * @return \DateTimeImmutable|null
     * @throws Exception
     */
    public static function parseDate(?string $dateString): ?\DateTimeImmutable
    {
        if ($dateString === null) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat("Ymd|", $dateString);

        if (false === $date) {
            throw new Exception("Invalid date, expected in yyyymmdd format, got \"{$dateString}\".");
        }

        return $date;
    }

    /**
     * Format a date time according to Twinfield specifications.
     *
     * @param \DateTimeInterface|null $datetime
     * @return string|null
     */
    public static function formatDateTime(?\DateTimeInterface $datetime): ?string
    {
        if ($datetime === null) {
            return null;
        }

        return $datetime->format("YmdHis");
    }

    /**
     * Parse a date time string from a Twinfield XML.
     *
     * @param string|null $dateString
     * @return \DateTimeImmutable|null
     * @throws Exception
     */
    public static function parseDateTime(?string $dateString): ?\DateTimeImmutable
    {
        if ($dateString === null) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat("YmdHis", $dateString);

        if (false === $date) {
            throw new Exception("Invalid date, expected in yyyymmddhhiiss format, got \"{$dateString}\".");
        }

        return $date;
    }

    /**
     * @param bool|null $boolean
     * @return string|null
     */
    public static function formatBoolean(?bool $boolean): ?string
    {
        if ($boolean === null) {
            return null;
        }

        return $boolean ? "true" : "false";
    }

    /**
     * @param string|null $value
     * @return bool|null
     */
    public static function parseBoolean(?string $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param HasCodeInterface|null $object
     * @return string|null
     */
    public static function objectToStr(?HasCodeInterface $object): ?string
    {
        if ($object === null) {
            return null;
        }

        return $object->getCode();
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
