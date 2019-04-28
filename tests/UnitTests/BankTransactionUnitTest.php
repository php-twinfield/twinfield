<?php
namespace PhpTwinfield\UnitTests;

use InvalidArgumentException;
use Money\Currency;
use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\BankTransactionLine;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\SalesTransactionLine;

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
        $this->bankTransaction->setStartvalue(Money::EUR(100));

        $this->assertEquals(new Currency('EUR'), $this->bankTransaction->getCurrency());
        $this->assertEquals(Money::EUR(100), $this->bankTransaction->getStartvalue());
        $this->assertEquals(Money::EUR(100), $this->bankTransaction->getClosevalue());
    }

    public function testSetCurrencyWithoutStartValue()
    {
        $this->bankTransaction->setCurrency(new Currency('EUR'));
        $this->assertEquals(new Currency('EUR'), $this->bankTransaction->getCurrency());
    }

    public function testSetCurrencyWithZeroStartValue()
    {
        $this->bankTransaction->setStartvalue(Money::EUR(0));
        $this->bankTransaction->setCurrency(new Currency('EUR'));

        $this->assertEquals(new Currency('EUR'), $this->bankTransaction->getCurrency());
    }

    public function testSetCurrencyWithStartValue()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->bankTransaction->setStartvalue(Money::EUR(100));
        $this->bankTransaction->setCurrency(new Currency('EUR'));
    }

    public function testAddLineWithWrongTransactionLine()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->bankTransaction->setStartvalue(Money::EUR(100));
        $this->bankTransaction->addLine(new SalesTransactionLine());
    }

    public function testAddLineUpdatesCloseValue()
    {
        $this->bankTransaction->setStartvalue(Money::EUR(100));

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

        $this->assertEquals(Money::EUR(200), $this->bankTransaction->getClosevalue());
    }
}
