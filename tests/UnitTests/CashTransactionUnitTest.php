<?php
namespace PhpTwinfield\UnitTests;

use InvalidArgumentException;
use Money\Money;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\CashTransactionLine;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\SalesTransactionLine;

class CashTransactionUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CashTransaction
     */
    private $cashTransaction;

    protected function setUp()
    {
        $this->cashTransaction = new CashTransaction();
    }

    public function testSetStartValue()
    {
        $this->assertSame($this->cashTransaction, $this->cashTransaction->setStartvalue(Money::EUR(100)), "Fluid interface is expected");

        $this->assertSame('EUR', $this->cashTransaction->getCurrency());
        $this->assertEquals(Money::EUR(100), $this->cashTransaction->getStartvalue());
        $this->assertEquals(Money::EUR(100), $this->cashTransaction->getClosevalue());
    }

    public function testSetCurrencyWithoutStartValue()
    {
        $this->assertSame($this->cashTransaction, $this->cashTransaction->setCurrency('EUR'), "Fluid interface is expected");
        $this->assertSame('EUR', $this->cashTransaction->getCurrency());
    }

    public function testSetCurrencyWithZeroStartValue()
    {
        $this->cashTransaction->setStartvalue(Money::EUR(0));
        $this->cashTransaction->setCurrency('EUR');

        $this->assertSame('EUR', $this->cashTransaction->getCurrency());
    }

    public function testSetCurrencyWithStartValue()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->cashTransaction->setStartvalue(Money::EUR(100));
        $this->cashTransaction->setCurrency('EUR');
    }

    public function testAddLineWithWrongTransactionLine()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->cashTransaction->setStartvalue(Money::EUR(100));
        $this->cashTransaction->addLine(new SalesTransactionLine());
    }

    public function testAddLineUpdatesCloseValue()
    {
        $this->cashTransaction->setStartvalue(Money::EUR(100));

        $totalLine = new CashTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setValue(Money::EUR(0));

        $detailLine1 = new CashTransactionLine();
        $detailLine1
            ->setLineType(LineType::DETAIL())
            ->setValue(Money::EUR(43555));

        $detailLine2 = new CashTransactionLine();
        $detailLine2
            ->setLineType(LineType::DETAIL())
            ->setValue(Money::EUR(-43455));

        $this->cashTransaction->addLine($totalLine);
        $this->cashTransaction->addLine($detailLine1);
        $this->cashTransaction->addLine($detailLine2);

        $this->assertEquals(Money::EUR(200), $this->cashTransaction->getClosevalue());
    }
}