<?php

declare(strict_types=1);

namespace IntegrationTests;

use PhpTwinfield\ApiConnectors\VatCodeApiConnector;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\IntegrationTests\BaseIntegrationTest;
use PhpTwinfield\Response\Response;

final class VatCodeIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var VatCodeApiConnector
     */
    private $vatCodeApiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vatCodeApiConnector = new VatCodeApiConnector($this->connection);
    }

    public function testGetVatCodeDetailWorks()
    {
        $response = Response::fromString(
            file_get_contents(__DIR__ . '/resources/vatCodeGetResponse.xml')
        );

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\VatCode::class))
            ->willReturn($response);

        $vatCode = $this->vatCodeApiConnector->get('VH', $this->office);

        $this->assertSame('VH', $vatCode->getCode());
        $this->assertSame('BTW Hoog', $vatCode->getName());
        $this->assertSame('VH', $vatCode->getShortName());
        $this->assertSame('8b16d247-81de-4334-8810-8dcb30386f42', $vatCode->getUID());
        $this->assertEquals(new \DateTimeImmutable('2004-10-27 10:13:42'), $vatCode->getCreated());
        $this->assertEquals(new \DateTimeImmutable('2004-10-27 10:13:42'), $vatCode->getModified());
        $this->assertSame(0, $vatCode->getTouched());
        $this->assertSame('JDOE', $vatCode->getUser());
        $this->assertCount(2, $vatCode->getPercentages());

        [$percentage1, $percentage2] = $vatCode->getPercentages();

        $this->assertSame('active', $percentage1->getStatus());
        $this->assertTrue($percentage1->isInUse());
        $this->assertEquals(new \DateTimeImmutable('2000-01-01'), $percentage1->getDate());
        $this->assertSame(19.0, $percentage1->getPercentage());
        $this->assertEquals(new \DateTimeImmutable('2013-10-02 11:40:36'), $percentage1->getCreated());
        $this->assertSame('BTW Hoog', $percentage1->getName());
        $this->assertSame('VH', $percentage1->getShortname());
        $this->assertSame('JDOE', $percentage1->getUser());
        $this->assertCount(1, $percentage1->getAccounts());

        [$account] = $percentage1->getAccounts();
        $this->assertSame('1', $account->getId());
        $this->assertSame('17120', $account->getDim1());
        $this->assertSame('NL', $account->getGroupCountry());
        $this->assertSame('NL1A', $account->getGroup());
        $this->assertSame(100.0, $account->getPercentage());
        $this->assertEquals(LineType::VAT(), $account->getLineType());

        $this->assertSame('active', $percentage2->getStatus());
        $this->assertTrue($percentage2->isInUse());
        $this->assertEquals(new \DateTimeImmutable('2012-10-01'), $percentage2->getDate());
        $this->assertSame(21.0, $percentage2->getPercentage());
        $this->assertEquals(new \DateTimeImmutable('2013-10-02 11:40:36'), $percentage2->getCreated());
        $this->assertSame('BTW Hoog', $percentage2->getName());
        $this->assertSame('VH', $percentage2->getShortname());
        $this->assertSame('JDOE', $percentage2->getUser());
        $this->assertCount(1, $percentage2->getAccounts());

        [$account] = $percentage2->getAccounts();
        $this->assertSame('1', $account->getId());
        $this->assertSame('17120', $account->getDim1());
        $this->assertSame('NL', $account->getGroupCountry());
        $this->assertSame('NL1A', $account->getGroup());
        $this->assertSame(100.0, $account->getPercentage());
        $this->assertEquals(LineType::VAT(), $account->getLineType());
    }
}
