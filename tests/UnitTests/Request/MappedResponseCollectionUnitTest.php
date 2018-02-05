<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\BankTransactionApiConnector;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class MappedResponseCollectionUnitTest extends TestCase
{
    /**
     * @var BankTransactionApiConnector
     */
    protected $apiConnector;

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processXmlService;

    public function setUp()
    {
        parent::setUp();

        $this->processXmlService = $this->createPartialMock(ProcessXmlService::class, []);
    }

    public function testMappedResponseCollection()
    {
        $response = $this->createFakeResponse();
        $collection = $this->processXmlService->mapAll([$response], "someResource", function() {});

        $this->assertTrue($collection->hasFailedResponses());
        $this->assertEquals(2, $collection->countFailedResponses());
        $this->assertCount(2, $collection->getFailedResponses());
        $this->assertTrue($collection->hasSuccessfulResponses());
        $this->assertEquals(3, $collection->countSuccessfulResponses());
        $this->assertCount(3, $collection->getSuccessfulResponses());

        $this->expectException(\InvalidArgumentException::class);
        $collection->append("somethingOtherThanAnIndividualMappedResponse");
        $collection->assertAllSuccessful();
    }

    public function createFakeResponse()
    {
        return Response::fromString("
<someResources result='0'>
    <someResource result='1'></someResource>
    <someResource result='0'></someResource>
    <someResource result='1'></someResource>
    <someResource result='0'></someResource>
    <someResource result='1'></someResource>
</someResources>");
    }
}