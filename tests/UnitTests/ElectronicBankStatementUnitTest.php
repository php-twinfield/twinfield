<?php

namespace PhpTwinfield\UnitTests;

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
        $ebs->setCurrencyFromString("HUF");

        $this->assertEquals("HUF", $ebs->getCurrency());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotChangeCurrencyOnceValueIsSet()
    {
        $ebs = new ElectronicBankStatement();
        $ebs->setStartValue(Money::GBP(1));

        $ebs->setCurrencyFromString("EUR");
    }
}
