<?php

declare(strict_types=1);

namespace PhpTwinfield\ApiConnectors;

final class DeclarationApiConnector extends BaseApiConnector
{
    public function listAllSummaries(string $companyCode)
    {
        $response = $this->getDeclarationService()->listAllSummaries($companyCode);

        // @todo: map response to types
    }
}

