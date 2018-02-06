<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\Response\Response;
use PHPUnit\Framework\TestCase;

class ResponseUnitTest extends TestCase
{
    public function testMultipleItemsSentIsSuccessFul()
    {
        $response = Response::fromString('<?xml version="1.0"?>
<statements target="electronicstatements" importduplicate="0">
    <statement target="electronicstatements" result="1">
        <!-- ... -->
    </statement>
    <!-- etc... -->
</statements>');
        $this->assertNull($response->assertSuccessful());
    }

    /**
     * @expectedException \PhpTwinfield\Exception
     * @expectedExceptionMessage Not all items were processed successfully by Twinfield: 0 success / 1 failed.
     */
    public function testMultipleItemsSentIsNotSuccessFul()
    {
        $response = Response::fromString('<?xml version="1.0"?>
<statements target="electronicstatements" importduplicate="0">
    <statement target="electronicstatements" result="0">
        <!-- ... -->
    </statement>
    <!-- etc... -->
</statements>');
        $response->assertSuccessful();
    }

    public function testTransactionSuccessfulSuccesfulIsSuccesful()
    {
        $response = Response::fromString('<?xml version="1.0"?>
<transactions result="1">
    <transaction result="1" location="temporary">
        <!-- ... -->
    </transaction>
    <!-- etc... -->
</transactions>
');
        $this->assertNull($response->assertSuccessful());
    }
}
