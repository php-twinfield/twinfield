<?php

declare(strict_types=1);

namespace PhpTwinfield\Services;

final class DeclarationService extends BaseService
{
    protected function WSDL(): string
    {
        return '/webservices/declarations.asmx?wsdl';
    }

    public function listAllSummaries(string $companyCode): \stdClass
    {
        return $this->GetAllSummaries([
            'CompanyCode' => $companyCode,
        ]);
    }
}
