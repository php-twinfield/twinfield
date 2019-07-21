<?php
namespace PhpTwinfield\UnitTests;

use InvalidArgumentException;
use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\BankTransactionLine;
use PhpTwinfield\Currency;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\SalesTransactionLine;
use PhpTwinfield\Util;

class BankTransactionUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var BankTransaction
     */
    private $bankTransaction;

    protected function setUp()
    {
        $this->bankTransaction = new BankTransaction();
    }

    public function testSetStartValue()
    {
        $this->bankTransaction->setCurrency(Currency::fromCode('EUR'));
        $this->bankTransaction->setStartValue(Money::EUR(100));

        $this->assertEquals('EUR', Util::objectToStr($this->bankTransaction->getCurrency()));
        $this->assertEquals(Money::EUR(100), $this->bankTransaction->getStartValue());
        $this->assertEquals(Money::EUR(100), $this->bankTransaction->getCloseValue());
    }

    public function testSetCurrencyWithoutStartValue()
    {
        $this->bankTransaction->setCurrency(Currency::fromCode('EUR'));
        $this->assertEquals('EUR', Util::objectToStr($this->bankTransaction->getCurrency()));
    }

    public function testSetCurrencyWithZeroStartValue()
    {
        $this->bankTransaction->setStartvalue(Money::EUR(0));
        $this->bankTransaction->setCurrency(Currency::fromCode('EUR'));

        $this->assertEquals('EUR', Util::objectToStr($this->bankTransaction->getCurrency()));
    }

    public function testSetCurrencyWithStartValue()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->bankTransaction->setStartValue(Money::EUR(100));
        $this->bankTransaction->setCurrency(Currency::fromCode('EUR'));
    }

    public function testAddLineWithWrongTransactionLine()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->bankTransaction->setStartValue(Money::EUR(100));
        $this->bankTransaction->addLine(new SalesTransactionLine());
    }

    public function testAddLineUpdatesCloseValue()
    {
        $this->bankTransaction->setStartValue(Money::EUR(100));

        $totalLine = new BankTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setValue(Money::EUR(0));

        $detailLine1 = new BankTransactionLine();
        $detailLine1
            ->setLineType(LineType::DETAIL())
            ->setValue(Money::EUR(43555));

        $detailLine2 = new BankTransactionLine();
        $detailLine2
            ->setLineType(LineType::DETAIL())
            ->setValue(Money::EUR(-43455));

        $this->bankTransaction->addLine($totalLine);
        $this->bankTransaction->addLine($detailLine1);
        $this->bankTransaction->addLine($detailLine2);

        $this->assertEquals(Money::EUR(200), $this->bankTransaction->getCloseValue());
    }
}
