<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Office;

class SelectOfficeService extends BaseService
{

    private const CHANGE_OK = "Ok";

    protected function WSDL(): string
    {
        return '/webservices/session.asmx?wsdl';
    }

    /**
     * Switches the current company in the API
     *
     * @param Office $office
     * @return bool
     */
    public function updateOffice(Office $office): bool
    {
        $result = $this->SelectCompany(
            ['company' => $office->getCode()]
        );

        return self::CHANGE_OK === $result->SelectCompanyResult;
    }
}