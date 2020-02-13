<?php

use PhpTwinfield\Transactions\TransactionLineFields\PeriodField;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpTwinfield\Transactions\TransactionLineFields\PeriodField
 */
class PeriodFieldUnitTest extends TestCase
{
    /** @test */
    public function testPeriodInCorrectFormat()
    {
        $periodFieldTrait = $this->getMockForTrait(PeriodField::class);

        $periodFieldTrait->setPeriod('2020/01');

        $this->assertEquals('2020/01', $periodFieldTrait->getPeriod());
    }

    /** @test */
    public function testPeriodCanBeNull()
    {
        $periodFieldTrait = $this->getMockForTrait(PeriodField::class);

        $periodFieldTrait->setPeriod(null);

        $this->assertNull($periodFieldTrait->getPeriod());
    }

    /** @test */
    public function testPeriodInIncorrectFormatThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $periodFieldTrait = $this->getMockForTrait(PeriodField::class);

        $periodFieldTrait->setPeriod('asdf');
    }
}
