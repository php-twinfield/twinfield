<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use Money\Money;

trait VatTurnoverFields
{
    /**
     * @var Money|null
     */
    private $vatTurnover;

    /**
     * @var Money|null
     */
    private $vatBaseTurnover;

    /**
     * @var Money|null
     */
    private $vatRepTurnover;

    /**
     * @return Money|null
     */
    public function getVatTurnover()
    {
        return $this->vatTurnover;
    }

    /**
     * @param Money|null $vatTurnover
     * @return $this
     */
    public function setVatTurnover(Money $vatTurnover = null)
    {
        $this->vatTurnover = $vatTurnover;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getVatBaseTurnover()
    {
        return $this->vatBaseTurnover;
    }

    /**
     * @param Money|null $vatBaseTurnover
     * @return $this
     */
    public function setVatBaseTurnover(Money $vatBaseTurnover = null)
    {
        $this->vatBaseTurnover = $vatBaseTurnover;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getVatRepTurnover()
    {
        return $this->vatRepTurnover;
    }

    /**
     * @param Money|null $vatRepTurnover
     * @return $this
     */
    public function setVatRepTurnover(Money $vatRepTurnover = null)
    {
        $this->vatRepTurnover = $vatRepTurnover;

        return $this;
    }
}

