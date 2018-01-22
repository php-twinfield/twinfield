<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\Transaction;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\LinesField;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionFields\RaiseWarningField;
use PhpTwinfield\Transactions\TransactionLineFields\DateField;
use PhpTwinfield\Transactions\TransactionLineFields\PeriodField;

/**
 * @todo $modificationDate The date/time on which the sales transaction was modified the last time. Read-only attribute.
 * @todo $user The user who created the sales transaction. Read-only attribute.
 * @todo $inputDate The date/time on which the transaction was created. Read-only attribute.
 */
abstract class BaseTransaction extends BaseObject implements Transaction
{
    use DestinyField;
    use AutoBalanceVatField;
    use OfficeField;
    use PeriodField;
    use FreeTextFields;
    use DateField;
    use RaiseWarningField;
    use LinesField;

    /**
     * @var string|null The transaction type code.
     */
    private $code;

    /**
     * @var int|null The transaction number.
     */
    private $number;

    /**
     * @var string|null The currency code.
     */
    private $currency;

    /**
     * @var string|null The sales transaction origin. Read-only attribute.
     */
    private $origin;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): BaseTransaction
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * When creating a new bank transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a bank transaction, the related transaction number should be provided.
     *
     * @param int|null $number
     * @return $this
     */
    public function setNumber(?int $number): BaseTransaction
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     * @return $this
     */
    public function setCurrency(?string $currency): BaseTransaction
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @param string|null $origin
     * @return $this
     */
    public function setOrigin(?string $origin): BaseTransaction
    {
        $this->origin = $origin;

        return $this;
    }

}
