<?php

namespace PhpTwinfield;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpTwinfield\Request\Query\Bank;

class BankStatement
{
    private ?Carbon $statementDate;

    private ?string $iban;

    private ?string $openingBalance;

    private ?string $closingBalance;

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
        $this->statementDate = Carbon::parse( $statementDate );
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

    public function getOpeningBalance(): ?string
    {
        return $this->openingBalance;
    }

    public function setOpeningBalance(string $openingBalance): BankStatement
    {
        $this->openingBalance = $openingBalance;
        return $this;
    }

    public function getClosingBalance(): ?string
    {
        return $this->closingBalance;
    }

    public function setClosingBalance(string $closingBalance): BankStatement
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

    public function getLinesTotalAmount(): string
    {
        $total = "0.00";

        foreach( $this->lines as $line )
        {
            $total = bcadd( $total, $line->getAmount(), 2 );
        }

        return $total;
    }

    public function checkLines()
    {
        $total = $this->getLinesTotalAmount();

        return( bccomp( bcadd( $this->openingBalance, $total,2), $this->closingBalance, 2 ) == 0 );
    }
}
