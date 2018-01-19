<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\Util;

class BankTransactionMapper extends BaseMapper
{
    public static function map(\DOMDocument $document): BankTransaction
    {
        $banktransaction = new BankTransaction();

        $element = $document->getElementsByTagName("transaction")[0];

        $banktransaction->setAutoBalanceVat(Util::parseBoolean($element->attributes["autobalancevat"]->value));
        $banktransaction->setRaiseWarning(Util::parseBoolean($element->attributes["raisewarning"]->value));

        self::setFromTagValue($document, "code", [$banktransaction, "setCode"]);
        self::setFromTagValue($document, "office", [$banktransaction, "setOffice"]);
        self::setFromTagValue($document, "period", [$banktransaction, "setPeriod"]);
        self::setFromTagValue($document, "startvalue", [$banktransaction, "setStartValue"]);

        self::setFromTagValue($document, "statementnumber", [$banktransaction, "setStatementNumber"]);
        self::setFromTagValue($document, "number", [$banktransaction, "setNumber"]);

        return $banktransaction;
    }
}