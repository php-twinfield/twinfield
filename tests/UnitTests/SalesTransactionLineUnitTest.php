<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\SalesTransactionLine;

class SalesTransactionLineUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SalesTransactionLine
     */
    private $line;

    protected function setUp()
    {
        $this->line = new SalesTransactionLine();
    }

    public function testSetVatTurnover()
    {
        $this->line->setType(LineType::VAT());

        $this->assertSame($this->line, $this->line->setVatTurnover(Money::EUR(1)), "Fluid interface is expected");
        $this->assertEquals(Money::EUR(1), $this->line->getVatTurnover());
    }

    public function testSetVatBaseTurnover()
    {
        $this->line->setType(LineType::VAT());

        $this->assertSame($this->line, $this->line->setVatBaseTurnover(Money::EUR(1)), "Fluid interface is expected");
        $this->assertEquals(Money::EUR(1), $this->line->getVatBaseTurnover());
    }

    public function testSetVatRepTurnover()
    {
        $this->line->setType(LineType::VAT());

        $this->assertSame($this->line, $this->line->setVatRepTurnover(Money::EUR(1)), "Fluid interface is expected");
        $this->assertEquals(Money::EUR(1), $this->line->getVatRepTurnover());
    }
}
