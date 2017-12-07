<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\Enums\Destiny;

trait DestinyField
{
    /**
     * Attribute to indicate the destiny of the bank transaction. Only used in the request XML.
     *
     * @var Destiny
     */
    private $destiny;

    public function getDestiny(): Destiny
    {
        return $this->destiny;
    }

    /**
     * @param Destiny $destiny
     * @return $this
     */
    public function setDestiny(Destiny $destiny): self
    {
        $this->destiny = $destiny;

        return $this;
    }
}