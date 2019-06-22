<?php

namespace PhpTwinfield\UnitTests;

use InvalidArgumentException;
use Money\Money;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\CashTransactionLine;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchStatus;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Util;

class CashTransactionLineUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CashTransactionLine
     */
    private $line;

    protected function setUp()
    {
        $this->line = new CashTransactionLine();
    }

    public function testSetTransaction()
    {
        $cashTransaction = new CashTransaction();
        $this->line->setTransaction($cashTransaction);

        $this->assertEquals($cashTransaction, $this->line->getTransaction());
    }

    public function testCanNotSetDifferentTransaction()
    {
        $this->expectException(InvalidArgumentException::class);

        $transaction = new SalesTransaction();
        $this->line->setTransaction($transaction);
    }

    public function testCanNotSetTransactionIfTransactionIsAlreadySet()
    {
        $this->expectException(InvalidArgumentException::class);

        $cashTransaction1 = new CashTransaction();
        $cashTransaction2 = new CashTransaction();
        $this->line->setTransaction($cashTransaction1);
        $this->line->setTransaction($cashTransaction2);
    }

    public function testSetDim2()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setDim2(\PhpTwinfield\CostCenter::fromCode('test')), 'Fluid interface is expected');
        $this->assertSame('test', Util::objectToStr($this->line->getDim2()));
    }

    public function testCanNotSetDim2IfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Dimension 2 is invalid for line class PhpTwinfield\CashTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setDim2('test');
    }

    public function testSetDim3()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setDim3(\PhpTwinfield\Project::fromCode('test')), 'Fluid interface is expected');
        $this->assertSame('test', Util::objectToStr($this->line->getDim3()));
    }

    public function testCanNotSetDim3IfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Dimension 3 is invalid for line class PhpTwinfield\CashTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setDim3('test');
    }

    public function testSetMatchStatus()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setMatchStatus(MatchStatus::MATCHED()), 'Fluid interface is expected');
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\MatchStatus');
        $this->assertSame($ReflectObject->getConstant('MATCHED'), (string)$this->line->getMatchStatus());
    }

    public function testCanNotSetMatchStatusOtherThanNotMatchableIfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Invalid match status \'matched\' for line class PhpTwinfield\CashTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setMatchStatus(MatchStatus::MATCHED());
    }

    public function testSetMatchLevel()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setMatchLevel(1), 'Fluid interface is expected');
        $this->assertSame(1, $this->line->getMatchLevel());
    }

    public function testCanNotSetMatchLevelIfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Invalid field \'matchlevel\' for line class PhpTwinfield\CashTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setMatchLevel(1);
    }

    public function testSetBaseValueOpen()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setBaseValueOpen(Money::EUR(100)), 'Fluid interface is expected');
        $this->assertEquals(Money::EUR(100), $this->line->getBaseValueOpen());
    }

    public function testCanNotSetBaseValueOpenIfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Invalid field \'basevalueopen\' for line class PhpTwinfield\CashTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setBaseValueOpen(Money::EUR(100));
    }

    public function testSetVatTurnover()
    {
        $this->line->setLineType(LineType::VAT());

        $this->assertEquals($this->line, $this->line->setVatTurnover(Money::EUR(100)), 'Fluid interface is expected');
        $this->assertEquals(Money::EUR(100), $this->line->getVatTurnover());
    }

    public function testCanNotSetVatTurnoverIfLineTypeIsNotVat()
    {
        $this->expectExceptionMessage('Invalid field \'vatturnover\' for line class PhpTwinfield\CashTransactionLine and type \'detail\'.');

        $this->line->setLineType(LineType::DETAIL());
        $this->line->setVatTurnover(Money::EUR(100));
    }

    public function testSetVatBaseTurnover()
    {
        $this->line->setLineType(LineType::VAT());

        $this->assertEquals($this->line, $this->line->setVatBaseTurnover(Money::EUR(100)), 'Fluid interface is expected');
        $this->assertEquals(Money::EUR(100), $this->line->getVatBaseTurnover());
    }

    public function testCanNotSetVatBaseTurnoverIfLineTypeIsNotVat()
    {
        $this->expectExceptionMessage('Invalid field \'vatbaseturnover\' for line class PhpTwinfield\CashTransactionLine and type \'detail\'.');

        $this->line->setLineType(LineType::DETAIL());
        $this->line->setVatBaseTurnover(Money::EUR(100));
    }

    public function testSetVatRepTurnover()
    {
        $this->line->setLineType(LineType::VAT());

        $this->assertEquals($this->line, $this->line->setVatRepTurnover(Money::EUR(100)), 'Fluid interface is expected');
        $this->assertEquals(Money::EUR(100), $this->line->getVatRepTurnover());
    }

    public function testCanNotSetVatRepTurnoverIfLineTypeIsNotVat()
    {
        $this->expectExceptionMessage('Invalid field \'vatrepturnover\' for line class PhpTwinfield\CashTransactionLine and type \'detail\'.');

        $this->line->setLineType(LineType::DETAIL());
        $this->line->setVatRepTurnover(Money::EUR(100));
    }
}
