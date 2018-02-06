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
     * @param bool          $isIncomingTransactionType
     * @param Money         $value
     * @param DebitCredit   $expectedDebitCredit
     *
     * @dataProvider dpTestSetValueAlsoSetsDebitCredit
     */
    public function testSetValueAlsoSetsDebitCredit(?LineType $lineType, bool $isIncomingTransactionType, Money $value, DebitCredit $expectedDebitCredit)
    {
        /** @var ValueFields|PHPUnit_Framework_MockObject_MockObject $valueFieldsTrait */
        $valueFieldsTrait = $this->getMockForTrait(ValueFields::class);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('getLineType')
            ->willReturn($lineType);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('isIncomingTransactionType')
            ->willReturn($isIncomingTransactionType);

        $valueFieldsTrait->setValue($value);

        $this->assertEquals($expectedDebitCredit, $valueFieldsTrait->getDebitCredit());
    }

    public function dpTestSetValueAlsoSetsDebitCredit(): array
    {
        return [
            /*
             * If line type = total
             * - In case of a 'normal' SALES transaction `debit`.
             * - In case of a credit SALES transaction `credit`.
             */
            'positive total incoming'             => [LineType::TOTAL(),  true,  Money::EUR( 1), DebitCredit::DEBIT()],
            'negative total incoming'             => [LineType::TOTAL(),  true,  Money::EUR(-1), DebitCredit::CREDIT()],
            /*
             * If line type = total
             * - In case of a 'normal' PURCHASE transaction `credit`.
             * - In case of a credit PURCHASE transaction `debit`.
             */
            'positive total outgoing'             => [LineType::TOTAL(),  false, Money::EUR( 1), DebitCredit::CREDIT()],
            'negative total outgoing'             => [LineType::TOTAL(),  false, Money::EUR(-1), DebitCredit::DEBIT()],

            /*
             * If line type = detail or vat
             * - In case of a 'normal' SALES transaction `credit`.
             * - In case of a credit SALES transaction `debit`.
             */
            'positive detail incoming'            => [LineType::DETAIL(), true,  Money::EUR( 1), DebitCredit::CREDIT()],
            'positive vat incoming'               => [LineType::VAT(),    true,  Money::EUR( 1), DebitCredit::CREDIT()],
            'positive without line type incoming' => [null,               true,  Money::EUR( 1), DebitCredit::CREDIT()],
            'negative detail incoming'            => [LineType::DETAIL(), true,  Money::EUR(-1), DebitCredit::DEBIT()],
            'negative vat incoming'               => [LineType::VAT(),    true,  Money::EUR(-1), DebitCredit::DEBIT()],
            'negative without line type incoming' => [null,               true,  Money::EUR(-1), DebitCredit::DEBIT()],
            /*
             * If line type = detail or vat
             * - In case of a 'normal' PURCHASE transaction `debit`.
             * - In case of a credit PURCHASE transaction `credit`.
             */
            'positive detail outgoing'            => [LineType::DETAIL(), false, Money::EUR( 1), DebitCredit::DEBIT()],
            'positive vat outgoing'               => [LineType::VAT(),    false, Money::EUR( 1), DebitCredit::DEBIT()],
            'positive without line type outgoing' => [null,               false, Money::EUR( 1), DebitCredit::DEBIT()],
            'negative detail outgoing'            => [LineType::DETAIL(), false, Money::EUR(-1), DebitCredit::CREDIT()],
            'negative vat outgoing'               => [LineType::VAT(),    false, Money::EUR(-1), DebitCredit::CREDIT()],
            'negative without line type outgoing' => [null,               false, Money::EUR(-1), DebitCredit::CREDIT()],
        ];
    }

    /**
     * @param LineType|null $lineType
     * @param bool          $isIncomingTransactionType
     * @param DebitCredit   $newDebitCredit
     * @param bool          $expectedIsPositive
     *
     * @dataProvider dpTestSetDebitCreditUpdatesValueSign
     */
    public function testSetDebitCreditUpdatesValueSign(?LineType $lineType, bool $isIncomingTransactionType, DebitCredit $newDebitCredit, bool $expectedIsPositive)
    {
        /** @var ValueFields|PHPUnit_Framework_MockObject_MockObject $valueFieldsTrait */
        $valueFieldsTrait = $this->getMockForTrait(ValueFields::class);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('getLineType')
            ->willReturn($lineType);
        $valueFieldsTrait
            ->expects($this->any())
            ->method('isIncomingTransactionType')
            ->willReturn($isIncomingTransactionType);

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
             * - In case of a 'normal' SALES transaction `debit`.
             * - In case of a credit SALES transaction `credit`.
             */
            'debit total incoming'              => [LineType::TOTAL(),  true,  DebitCredit::DEBIT(),  true],
            'credit total incoming'             => [LineType::TOTAL(),  true,  DebitCredit::CREDIT(), false],
            /*
             * If line type = total
             * - In case of a 'normal' PURCHASE transaction `credit`.
             * - In case of a credit PURCHASE transaction `debit`.
             */
            'debit total outgoing'              => [LineType::TOTAL(),  false, DebitCredit::DEBIT(),  false],
            'credit total outgoing'             => [LineType::TOTAL(),  false, DebitCredit::CREDIT(), true],

            /*
             * If line type = detail or vat
             * - In case of a 'normal' SALES transaction `credit`.
             * - In case of a credit SALES transaction `debit`.
             */
            'debit detail incoming'             => [LineType::DETAIL(), true,  DebitCredit::DEBIT(),  false],
            'credit detail incoming'            => [LineType::DETAIL(), true,  DebitCredit::CREDIT(), true],
            'debit vat incoming'                => [LineType::VAT(),    true,  DebitCredit::DEBIT(),  false],
            'credit vat incoming'               => [LineType::VAT(),    true,  DebitCredit::CREDIT(), true],
            'debit without line type incoming'  => [null,               true,  DebitCredit::DEBIT(),  false],
            'credit without line type incoming' => [null,               true,  DebitCredit::CREDIT(), true],
            /*
             * If line type = detail or vat
             * - In case of a 'normal' PURCHASE transaction `debit`.
             * - In case of a credit PURCHASE transaction `credit`.
             */
            'debit detail outgoing'             => [LineType::DETAIL(), false, DebitCredit::DEBIT(),  true],
            'credit detail outgoing'            => [LineType::DETAIL(), false, DebitCredit::CREDIT(), false],
            'debit vat outgoing'                => [LineType::VAT(),    false, DebitCredit::DEBIT(),  true],
            'credit vat outgoing'               => [LineType::VAT(),    false, DebitCredit::CREDIT(), false],
            'debit without line type outgoing'  => [null,               false, DebitCredit::DEBIT(),  true],
            'credit without line type outgoing' => [null,               false, DebitCredit::CREDIT(), false],
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
        $valueFieldsTrait
            ->expects($this->any())
            ->method('isIncomingTransactionType')
            ->willReturn(true);

        $valueFieldsTrait->setDebitCredit(DebitCredit::DEBIT());
    }
}
