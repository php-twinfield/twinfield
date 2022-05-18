<?php

namespace PhpTwinfield;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Money\Currency;
use Money\Money;
use stdClass;

class BankStatement
{
    /**
     * @var Carbon|null
     */
    private ?Carbon $statementDate;

    /**
     * @var string|null
     */
    private ?string $iban;

    /**
     * @var Money|null
     */
    private ?Money $openingBalance;

    /**
     * @var Money|null
     */
    private ?Money $closingBalance;

    /**
     * @var Collection|null
     */
    private ?Collection $lines;

    /**
     * @var int
     */
    private int $number;

    /**
     * @var int
     */
    private int $subId;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var Currency|null
     */
    private ?Currency $currency = null;

    /**
     * @param stdClass $bankStatement
     * @throws Exception
     */
    public function __construct( stdClass $bankStatement )
    {
        $this->setStatementDate($bankStatement->StatementDate)
             ->setIban($bankStatement->Iban)
             ->setCurrency($bankStatement->Currency)
             ->setOpeningBalance($bankStatement->OpeningBalance)
             ->setClosingBalance($bankStatement->ClosingBalance)
             ->setCode($bankStatement->Code)
             ->setNumber($bankStatement->Number)
             ->setSubId($bankStatement->SubId)
             ->setLines($bankStatement->Lines->BankStatementLine);
    }

    /**
     * @return Carbon
     */
    public function getStatementDate(): Carbon
    {
        return $this->statementDate;
    }

    /**
     * @param string $statementDate
     * @return $this
     */
    public function setStatementDate(string $statementDate): BankStatement
    {
        $this->statementDate = Carbon::parse( $statementDate );
        return $this;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return $this
     */
    public function setIban(string $iban): BankStatement
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return Money|null
     */
    public function getOpeningBalance(): ?Money
    {
        return $this->openingBalance;
    }

    /**
     * @param string $openingBalance
     * @return $this
     */
    public function setOpeningBalance(string $openingBalance): BankStatement
    {
        $this->openingBalance = new Money( bcmul( $openingBalance, 100 ), $this->currency );
        return $this;
    }

    /**
     * @return Money|null
     */
    public function getClosingBalance(): ?Money
    {
        return $this->closingBalance;
    }

    /**
     * @param string $closingBalance
     * @return $this
     */
    public function setClosingBalance(string $closingBalance): BankStatement
    {
        $this->closingBalance = new Money( bcmul( $closingBalance, 100 ), $this->currency );
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
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): BankStatement
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSubId(): ?int
    {
        return $this->subId;
    }

    /**
     * @param string $subId
     * @return $this
     */
    public function setSubId(string $subId): BankStatement
    {
        $this->subId = $subId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): BankStatement
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return Currency
     * @throws Exception
     */
    public function getCurrency(): Currency
    {
        if( $this->currency == null ) {
            throw new Exception("Getting currency, but not initialized yet");
        }

        return $this->currency;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): BankStatement
    {
        $this->currency = new Currency( $currency );
        return $this;
    }


    /**
     * @return Collection|null
     */
    public function getLines(): ?Collection
    {
        return $this->lines;
    }

    /**
     * @param array|stdClass $lines
     * @return $this
     */
    public function setLines(array|stdClass $lines): BankStatement
    {
        $this->lines = new Collection();

        if (is_array($lines)) {
            foreach ($lines as $line) {
                $this->lines[] = new BankStatementLine($line, $this->currency);
            }
        } else {
            $this->lines[] = new BankStatementLine($lines, $this->currency);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLinesTotalAmount(): Money
    {
        $total = new Money( "0", $this->currency );

        foreach( $this->lines as $line )
        {
            $total = $total->add( $line->getAmount() );
        }

        return $total;
    }

    /**
     * @return bool
     */
    public function checkLines(): bool
    {
        return $this->openingBalance->add( $this->getLinesTotalAmount() )->equals( $this->closingBalance );
    }
}
