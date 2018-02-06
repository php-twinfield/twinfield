<?php

namespace PhpTwinfield\UnitTests;

use Money\Currency;
use Money\Money;
use PhpTwinfield\ElectronicBankStatement;
use PHPUnit\Framework\TestCase;

class ElectronicBankStatementUnitTest extends TestCase
{
    /**
     * @link https://github.com/php-twinfield/twinfield/issues/45
     */
    public function testCanSetCurrencyManually()
    {
        $ebs = new ElectronicBankStatement();
        $ebs->setCurrency(new Currency("HUF"));

        $this->assertEquals("HUF", $ebs->getCurrency());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotChangeCurrencyOnceValueIsSet()
    {
        $ebs = new ElectronicBankStatement();
        $ebs->setStartvalue(Money::GBP(1));

        $ebs->setCurrency(new Currency("EUR"));
    }
}
