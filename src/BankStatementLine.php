<?php

namespace PhpTwinfield;

use Carbon\Carbon;
use PhpTwinfield\Request\Query\Bank;

class BankStatementLine
{
    private int $lineId;

    private string $amount;

    private string $baseAmount;

    private string $contraAccountName;

    private string $contraIban;

    private string $description;

    private string $endToEndId;

    private string $paymentReference;

    private ?string $recognition;

    private string $reference;

    private string $returnReason;

    private string $transactionTypeId;

    private string $dimension1;

    private string $dimension2;

    public function __construct( ?\stdClass $bankStatementLine = null )
    {
        $this->setLineId( $bankStatementLine->LineId )
             ->setAmount( $bankStatementLine->Amount )
             ->setBaseAmount( $bankStatementLine->BaseAmount )
             ->setContraAccountName( $bankStatementLine->ContraAccountName )
             ->setContraIban( $bankStatementLine->ContraIban )
             ->setDescription( $bankStatementLine->Description )
             ->setEndToEndId( $bankStatementLine->EndToEndId )
             ->setPaymentReference( $bankStatementLine->PaymentReference )
             ->setRecognition( $bankStatementLine->Recognition )
             ->setReference( $bankStatementLine->Reference )
             ->setReturnReason( $bankStatementLine->ReturnReason )
             ->setTransactionTypeId( $bankStatementLine->TransactionTypeId )
             ->setDimension1( $bankStatementLine->Allocations->BankStatementAllocation->Dimension1 )
             ->setDimension2( $bankStatementLine->Allocations->BankStatementAllocation->Dimension2 );
    }

    public function getTransactionTypeId()
    {
        return $this->getTransactionTypeId();
    }

    public function setTransactionTypeId(string $transactionTypeId ): BankStatementLine
    {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    public function getLineId(): int
    {
        return $this->lineId;
    }

    public function setLineId( int $lineId ): BankStatementLine
    {
        $this->lineId = $lineId;
        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount( float $amount ): BankStatementLine
    {
        $this->amount = $amount;
        return $this;
    }

    public function getBaseAmount(): string
    {
        return $this->baseAmount;
    }

    public function setBaseAmount( float $baseAmount ): BankStatementLine
    {
        $this->baseAmount = $baseAmount;
        return $this;
    }

    public function getContraAccountName(): string
    {
        return $this->contraAccountName;
    }

    public function setContraAccountName(string $contraAccountName): BankStatementLine
    {
        $this->contraAccountName = $contraAccountName;
        return $this;
    }

    public function getContraIban(): string
    {
        return $this->contraIban;
    }

    public function setContraIban(string $contraIban): BankStatementLine
    {
        $this->contraIban = $contraIban;
        return $this;
    }

    public function getDescription(): string  {
        return $this->description;
    }

    public function setDescription(string $description): BankStatementLine
    {
        $this->description = $description;
        return $this;
    }

    public function getEndToEndId(): string  {
        return $this->endToEndId;
    }

    public function setEndToEndId( string $endToEndId ): BankStatementLine
    {
        $this->endToEndId = $endToEndId;
        return $this;
    }

    public function getPaymentReference(): string  {
        return $this->paymentReference;
    }

    public function setPaymentReference( string $paymentReference ): BankStatementLine
    {
        $this->paymentReference = $paymentReference;
        return $this;
    }

    public function getRecognition(): ?string  {
        return $this->recognition;
    }

    public function setRecognition( ?string $recognition ): BankStatementLine
    {
        $this->recognition = $recognition;
        return $this;
    }

    public function getReference(): string  {
        return $this->reference;
    }

    public function setReference( string $reference ): BankStatementLine
    {
        $this->reference = $reference;
        return $this;
    }

    public function getReturnReason(): string  {
        return $this->returnReason;
    }

    public function setReturnReason( string $returnReason ): BankStatementLine
    {
        $this->returnReason = $returnReason;
        return $this;
    }

    public function getDimension1(): string  {
        return $this->dimension1;
    }

    public function setDimension1( string $dimension1 ): BankStatementLine
    {
        $this->dimension1 = $dimension1;
        return $this;
    }

    public function getDimension2(): string  {
        return $this->dimension2;
    }

    public function setDimension2( string $dimension2 ): BankStatementLine
    {
        $this->dimension2 = $dimension2;
        return $this;
    }
}
