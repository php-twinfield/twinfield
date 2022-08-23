<?php

declare(strict_types=1);

namespace IntegrationTests;

use PhpTwinfield\ApiConnectors\DeclarationApiConnector;
use PhpTwinfield\IntegrationTests\BaseIntegrationTest;

final class DeclarationApiConnectorTest extends BaseIntegrationTest
{
    /**
     * @var DeclarationApiConnector
     */
    private $declarationApiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->declarationApiConnector = new DeclarationApiConnector($this->connection);
    }

    public function testListAllSummaries(): void
    {
        $this->markTestIncomplete('todo');

        // @todo: mock the response that's returned from the listAllSummaries method on the DeclarationService
        $response = (object) [];

        $this->declarationService
            ->expects($this->once())
            ->method("listAllSummaries")
            ->with('1234')
            ->willReturn($response);

        $result = $this->declarationApiConnector->listAllSummaries('1234');

        // @todo: assert data is mapped as expected
    }
}
