<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\Transaction;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\CodeNumberOfficeFields;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\LinesField;
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
    use CodeNumberOfficeFields;
    use PeriodField;
    use FreeTextFields;
    use DateField;
    use RaiseWarningField;
    use LinesField;

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
