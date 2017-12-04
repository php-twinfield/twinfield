<?php

namespace PhpTwinfield;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

final class Util
{
    public static function formatMoney(Money $money): string
    {
        $decimalformatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $decimalformatter->format($money);
    }
}