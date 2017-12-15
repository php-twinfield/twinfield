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
     * @var int|null
     */
    private $baseline;

    /**
     * @return Money|null
     */
    public function getVatTurnover(): ?Money
    {
        return $this->vatTurnover;
    }

    /**
     * @param Money|null $vatTurnover
     * @return $this
     */
    public function setVatTurnover(?Money $vatTurnover)
    {
        $this->vatTurnover = $vatTurnover;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getVatBaseTurnover(): ?Money
    {
        return $this->vatBaseTurnover;
    }

    /**
     * @param Money|null $vatBaseTurnover
     * @return $this
     */
    public function setVatBaseTurnover(?Money $vatBaseTurnover)
    {
        $this->vatBaseTurnover = $vatBaseTurnover;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getVatRepTurnover(): ?Money
    {
        return $this->vatRepTurnover;
    }

    /**
     * @param Money|null $vatRepTurnover
     * @return $this
     */
    public function setVatRepTurnover(?Money $vatRepTurnover)
    {
        $this->vatRepTurnover = $vatRepTurnover;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBaseline(): ?int
    {
        return $this->baseline;
    }

    /**
     * @param int|null $baseline
     * @return $this
     */
    public function setBaseline(?int $baseline)
    {
        $this->baseline = $baseline;

        return $this;
    }
}

