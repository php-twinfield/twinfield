<?php

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpTwinfield\Transactions\TransactionLineFields\ValueFields
 */
class ValueFieldsUnitTest extends TestCase
{
    /**
     * @param LineType|null $lineType
     * @param Money         $value
     * @param DebitCredit   $expectedDebitCredit
     *
     * @dataProvider dpTestSetValueAlsoSetsDebitCredit
     */
    public function testSetValueAlsoSetsDebitCredit(?LineType $lineType, Money $value, DebitCredit $expectedDebitCredit)
    {
        /** @var ValueFields|PHPUnit_Framework_MockObject_MockObject $valueFieldsTrait */
        $valueFieldsTrait = $this->getMockForTrait(ValueFields::class);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('getLineType')
            ->willReturn($lineType);

        $valueFieldsTrait->setValue($value);

        $this->assertEquals($expectedDebitCredit, $valueFieldsTrait->getDebitCredit());
    }

    public function dpTestSetValueAlsoSetsDebitCredit(): array
    {
        return [
            /*
             * If line type = total
             * - In case of a 'normal' transaction `debit`.
             * - In case of a credit transaction `credit`.
             */
            'positive total'             => [LineType::TOTAL(),  Money::EUR( 1), DebitCredit::DEBIT()],
            'negative total'             => [LineType::TOTAL(),  Money::EUR(-1), DebitCredit::CREDIT()],
            /*
             * If line type = detail or vat
             * - In case of a 'normal' transaction `credit`.
             * - In case of a credit transaction `debit`.
             */
            'positive detail'            => [LineType::DETAIL(), Money::EUR( 1), DebitCredit::CREDIT()],
            'positive vat'               => [LineType::VAT(),    Money::EUR( 1), DebitCredit::CREDIT()],
            'positive without line type' => [null,               Money::EUR( 1), DebitCredit::CREDIT()],
            'negative detail'            => [LineType::DETAIL(), Money::EUR(-1), DebitCredit::DEBIT()],
            'negative vat'               => [LineType::VAT(),    Money::EUR(-1), DebitCredit::DEBIT()],
            'negative without line type' => [null,               Money::EUR(-1), DebitCredit::DEBIT()],
        ];
    }

    /**
     * @param LineType|null $lineType
     * @param DebitCredit   $newDebitCredit
     * @param bool          $expectedIsPositive
     *
     * @dataProvider dpTestSetDebitCreditUpdatesValueSign
     */
    public function testSetDebitCreditUpdatesValueSign(?LineType $lineType, DebitCredit $newDebitCredit, bool $expectedIsPositive)
    {
        /** @var ValueFields|PHPUnit_Framework_MockObject_MockObject $valueFieldsTrait */
        $valueFieldsTrait = $this->getMockForTrait(ValueFields::class);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('getLineType')
            ->willReturn($lineType);

        $valueFieldsTrait->setValue(Money::EUR(1));
        $valueFieldsTrait->setDebitCredit($newDebitCredit);

        if ($expectedIsPositive) {
            $this->assertTrue($valueFieldsTrait->getSignedValue()->isPositive());
        } else {
            $this->assertTrue($valueFieldsTrait->getSignedValue()->isNegative());
        }
    }

    public function dpTestSetDebitCreditUpdatesValueSign(): array
    {
        return [
            /*
             * If line type = total
             * - In case of a 'normal' transaction `debit`.
             * - In case of a credit transaction `credit`.
             */
            'debit total'              => [LineType::TOTAL(),  DebitCredit::DEBIT(),  true],
            'credit total'             => [LineType::TOTAL(),  DebitCredit::CREDIT(), false],
            /*
             * If line type = detail or vat
             * - In case of a 'normal' transaction `credit`.
             * - In case of a credit transaction `debit`.
             */
            'debit detail'             => [LineType::DETAIL(), DebitCredit::DEBIT(),  false],
            'credit detail'            => [LineType::DETAIL(), DebitCredit::CREDIT(), true],
            'debit vat'                => [LineType::VAT(),    DebitCredit::DEBIT(),  false],
            'credit vat'               => [LineType::VAT(),    DebitCredit::CREDIT(), true],
            'debit without line type'  => [null,               DebitCredit::DEBIT(),  false],
            'credit without line type' => [null,               DebitCredit::CREDIT(), true],
        ];
    }

    public function testDebitCreditCanOnlyBeSetAfterValueIsSet()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var ValueFields|PHPUnit_Framework_MockObject_MockObject $valueFieldsTrait */
        $valueFieldsTrait = $this->getMockForTrait(ValueFields::class);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('getLineType')
            ->willReturn(null);

        $valueFieldsTrait->setDebitCredit(DebitCredit::DEBIT());
    }
}
