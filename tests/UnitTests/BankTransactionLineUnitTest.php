<?php

namespace PhpTwinfield\UnitTests;

use InvalidArgumentException;
use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\BankTransactionLine;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchStatus;
use PhpTwinfield\SalesTransaction;

class BankTransactionLineUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var BankTransactionLine
     */
    private $line;

    protected function setUp()
    {
        $this->line = new BankTransactionLine();
    }

    public function testSetTransaction()
    {
        $bankTransaction = new BankTransaction();
        $this->line->setTransaction($bankTransaction);

        $this->assertEquals($bankTransaction, $this->line->getTransaction());
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

        $bankTransaction1 = new BankTransaction();
        $bankTransaction2 = new BankTransaction();
        $this->line->setTransaction($bankTransaction1);
        $this->line->setTransaction($bankTransaction2);
    }

    public function testSetDim2()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setDim2FromString('test'), 'Fluid interface is expected');
        $this->assertSame('test', $this->line->getDim2ToString());
    }

    public function testCanNotSetDim2IfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Dimension 2 is invalid for line class PhpTwinfield\BankTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setDim2('test');
    }

    public function testSetDim3()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setDim3FromString('test'), 'Fluid interface is expected');
        $this->assertSame('test', $this->line->getDim3ToString());
    }

    public function testCanNotSetDim3IfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Dimension 3 is invalid for line class PhpTwinfield\BankTransactionLine and type \'vat\'.');

        $this->line->setLineType(LineType::VAT());
        $this->line->setDim3('test');
    }

    public function testSetMatchStatus()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setMatchStatus(MatchStatus::MATCHED()), 'Fluid interface is expected');
        $this->assertSame(MatchStatus::MATCHED(), $this->line->getMatchStatus());
    }

    public function testCanNotSetMatchStatusOtherThanNotMatchableIfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Invalid match status \'matched\' for line class PhpTwinfield\BankTransactionLine and type \'vat\'.');

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
        $this->expectExceptionMessage('Invalid field \'matchLevel\' for line class PhpTwinfield\BankTransactionLine and type \'vat\'.');

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
        $this->expectExceptionMessage('Invalid field \'baseValueOpen\' for line class PhpTwinfield\BankTransactionLine and type \'vat\'.');

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
        $this->expectExceptionMessage('Invalid field \'vatturnover\' for line class PhpTwinfield\BankTransactionLine and type \'detail\'.');

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
        $this->expectExceptionMessage('Invalid field \'vatbaseturnover\' for line class PhpTwinfield\BankTransactionLine and type \'detail\'.');

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
        $this->expectExceptionMessage('Invalid field \'vatrepturnover\' for line class PhpTwinfield\BankTransactionLine and type \'detail\'.');

        $this->line->setLineType(LineType::DETAIL());
        $this->line->setVatRepTurnover(Money::EUR(100));
    }

    public function testSetInvoiceNumber()
    {
        $this->line->setLineType(LineType::DETAIL());

        $this->assertEquals($this->line, $this->line->setInvoiceNumber('11001770'), 'Fluid interface is expected');
        $this->assertSame('11001770', $this->line->getInvoiceNumber());
    }

    public function testCanNotSetInvoiceNumberIfLineTypeIsNotDetail()
    {
        $this->expectExceptionMessage('Invalid field \'invoicenumber\' for line class PhpTwinfield\BankTransactionLine and type \'total\'.');

        $this->line->setLineType(LineType::TOTAL());
        $this->line->setInvoiceNumber('11001770');
    }
}
