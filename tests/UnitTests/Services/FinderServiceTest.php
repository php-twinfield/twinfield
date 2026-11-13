<?php

namespace PhpTwinfield\UnitTests\Services;

use PhpTwinfield\Services\FinderService;
use PHPUnit\Framework\TestCase;

class FinderServiceTest extends TestCase
{
    public function testAssociativeOptionsAreConvertedToArrayOfArrayOfString()
    {
        $this->assertSame(
            ['ArrayOfString' => [
                ['string' => ['dimtype', 'BAS']],
                ['string' => ['modifiedsince', '20240101000000']],
            ]],
            FinderService::toArrayOfArrayOfString(['dimtype' => 'BAS', 'modifiedsince' => '20240101000000'])
        );
    }

    public function testNameValuePairsAreConverted()
    {
        $this->assertSame(
            ['ArrayOfString' => [['string' => ['hidden', '1']]]],
            FinderService::toArrayOfArrayOfString([['hidden', 1]])
        );
    }

    public function testEmptyAndPreformattedOptionsArePassedThrough()
    {
        $preformatted = ['ArrayOfString' => [['string' => ['dimtype', 'PNL']]]];

        $this->assertSame([], FinderService::toArrayOfArrayOfString([]));
        $this->assertSame($preformatted, FinderService::toArrayOfArrayOfString($preformatted));
    }
}
