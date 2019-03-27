<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use Webmozart\Assert\Assert;

trait VatCodeField
{
    /**
     * @var null|string
     */
    private $vatCode;

    /**
     * @param null|string $vatCode
     * @return $this
     */
    public function setVatCode($vatCode = null)
    {
        Assert::lessThanEq(strlen($vatCode), 16);
        $this->vatCode = $vatCode;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getVatCode()
    {
        return $this->vatCode;
    }
}