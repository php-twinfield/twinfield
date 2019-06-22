<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\Currency;
use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Util;
use PHPUnit\Framework\TestCase;

class ElectronicBankStatementUnitTest extends TestCase
{
    /**
     * @link https://github.com/php-twinfield/twinfield/issues/45
     */
    public function testCanSetCurrencyManually()
    {
        $ebs = new ElectronicBankStatement();
        $ebs->setCurrency(Currency::fromCode("HUF"));

        $this->assertEquals("HUF", Util::objectToStr($ebs->getCurrency()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotChangeCurrencyOnceValueIsSet()
    {
        $ebs = new ElectronicBankStatement();
        $ebs->setStartValue(Money::GBP(1));

        $ebs->setCurrency(Currency::fromCode("EUR"));
    }
}
