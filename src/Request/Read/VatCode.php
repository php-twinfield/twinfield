<?php

declare(strict_types=1);

namespace PhpTwinfield\Request\Read;

final class VatCode extends Read
{
    public function __construct(string $office, string $code)
    {
        parent::__construct();

        $this->add('type', 'vat');

        $this->add('office', $office);
        $this->add('code', $code);
    }
}
