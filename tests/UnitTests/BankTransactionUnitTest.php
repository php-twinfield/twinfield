<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;

class BankTransactionUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testCanGetReferenceFromLine()
    {
        $bank = new BankTransaction();
        $bank->setOffice(Office::fromCode("XXX99999"));
        $bank->setNumber("201300003");
        $bank->getNumber();
        $bank->setCode("MEMO");

        $line = new Detail();
        $line->setId(1);
        $line->setValue(Money::EUR(1));

        $bank->addLine($line);

        $reference = $line->getReference();

        $this->assertEquals(Office::fromCode("XXX99999"), $reference->getOffice());
        $this->assertEquals("201300003", $reference->getNumber());
        $this->assertEquals("1", $reference->getLineId());
        $this->assertEquals("MEMO", $reference->getCode());
    }
}
