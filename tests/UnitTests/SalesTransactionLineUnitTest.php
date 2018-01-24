<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Office;
use PhpTwinfield\SalesTransaction;
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
        $this->line->setLineType(LineType::VAT());

        $this->assertSame($this->line, $this->line->setVatTurnover(Money::EUR(1)), "Fluid interface is expected");
        $this->assertEquals(Money::EUR(1), $this->line->getVatTurnover());
    }

    public function testSetVatBaseTurnover()
    {
        $this->line->setLineType(LineType::VAT());

        $this->assertSame($this->line, $this->line->setVatBaseTurnover(Money::EUR(1)), "Fluid interface is expected");
        $this->assertEquals(Money::EUR(1), $this->line->getVatBaseTurnover());
    }

    public function testSetVatRepTurnover()
    {
        $this->line->setLineType(LineType::VAT());

        $this->assertSame($this->line, $this->line->setVatRepTurnover(Money::EUR(1)), "Fluid interface is expected");
        $this->assertEquals(Money::EUR(1), $this->line->getVatRepTurnover());
    }

    public function testCanGetReferenceFromLine()
    {
        $purchase = new SalesTransaction();
        $purchase->setOffice(Office::fromCode("XXX99999"));
        $purchase->setNumber("201300021");
        $purchase->setCode("INK");

        $line = new SalesTransactionLine();
        $line->setId(2);

        $purchase->addLine($line);

        $reference = $line->getReference();

        $this->assertEquals(Office::fromCode("XXX99999"), $reference->getOffice());
        $this->assertEquals("201300021", $reference->getNumber());
        $this->assertEquals("2", $reference->getLineId());
        $this->assertEquals("INK", $reference->getCode());
    }
}
