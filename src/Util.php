<?php

namespace PhpTwinfield;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Webmozart\Assert\Assert;

final class Util
{
    public static function formatMoney(Money $money): string
    {
        Assert::true($money->isPositive() || $money->isZero());

        $decimalformatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $decimalformatter->format($money);
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
     * Get all the traits an object uses, includes traits from all parent classes.
     *
     * @param string $trait
     * @param object $object
     * @return bool
     */
    public static function objectUses(string $trait, $object): bool
    {
        Assert::object($object);

        $traits = array_reduce(class_parents($object), function(array $carry, string $item) {
            return array_merge($carry, class_uses($item));
        }, class_uses(get_class($object)));

        return in_array($trait, $traits);
    }
}