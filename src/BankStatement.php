<?php

namespace PhpTwinfield;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpTwinfield\Request\Query\Bank;

class BankStatement
{
    private ?Carbon $statementDate;

    private ?string $iban;

    private ?float $openingBalance;

    private ?float $closingBalance;

    private ?Collection $lines;

    public function __construct( ?\stdClass $bankStatement = null )
    {
        if( $bankStatement !== null ) {
            $this->setStatementDate($bankStatement->StatementDate)
                 ->setIban($bankStatement->Iban)
                 ->setOpeningBalance($bankStatement->OpeningBalance)
                 ->setClosingBalance($bankStatement->ClosingBalance)
                 ->setLines($bankStatement->Lines->BankStatementLine);
        }
    }

    public function getStatementDate(): string
    {
        return $this->statementDate;
    }

    public function setStatementDate(string $statementDate): BankStatement
    {
        $this->statementDate = Carbon::createFromFormat( "Y-m-d\TH:m:s", $statementDate );
        return $this;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): BankStatement
    {
        $this->iban = $iban;
        return $this;
    }

    public function getOpeningBalance(): ?float
    {
        return $this->openingBalance;
    }

    public function setOpeningBalance(float $openingBalance): BankStatement
    {
        $this->openingBalance = $openingBalance;
        return $this;
    }

    public function getClosingBalance(): ?float
    {
        return $this->closingBalance;
    }

    public function setClosingBalance(float $closingBalance): BankStatement
    {
        $this->closingBalance = $closingBalance;
        return $this;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function setLines( array $lines )
    {
        $this->lines = new Collection();

        foreach( $lines as $line ) {
            $this->lines[] = new BankStatementLine($line);
        }

        return $this;
    }

    public function checkLines()
    {
        $total = 0.00;

        foreach( $this->lines as $line )
        {
            $total += $line->getAmount();
        }

        return( $this->openingBalance + $total == $this->closingBalance );
    }
}
