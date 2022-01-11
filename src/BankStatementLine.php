<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;
use stdClass;

class BankStatementLine
{
    private int $lineId;

    private ?Currency $currency = null;

    private Money $amount;

    private Money $baseAmount;

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

    /**
     * Constructor for BankStatementLine, including the currency from the BankStatement
     *
     * @param stdClass $bankStatementLine
     * @param Currency $currency
     */
    public function __construct(stdClass $bankStatementLine, Currency &$currency )
    {
        $this->setLineId( $bankStatementLine->LineId )
             ->setCurrency( $currency )
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

    /**
     * @return mixed
     */
    public function getTransactionTypeId()
    {
        return $this->transactionTypeId;
    }

    /**
     * @param string $transactionTypeId
     * @return $this
     */
    public function setTransactionTypeId(string $transactionTypeId ): BankStatementLine
    {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLineId(): int
    {
        return $this->lineId;
    }

    /**
     * @param int $lineId
     * @return $this
     */
    public function setLineId(int $lineId ): BankStatementLine
    {
        $this->lineId = $lineId;
        return $this;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function setAmount(string $amount ): BankStatementLine
    {
        $this->amount = new Money( bcmul( $amount, 100 ), $this->getCurrency() );
        return $this;
    }

    /**
     * @return Money
     */
    public function getBaseAmount(): Money
    {
        return $this->baseAmount;
    }

    /**
     * @param string $baseAmount
     * @return $this
     */
    public function setBaseAmount(string $baseAmount ): BankStatementLine
    {
        $this->baseAmount = new Money( bcmul( $baseAmount, 100 ), $this->getCurrency() );
        return $this;
    }

    /**
     * @return string
     */
    public function getContraAccountName(): string
    {
        return $this->contraAccountName;
    }

    /**
     * @param string $contraAccountName
     * @return $this
     */
    public function setContraAccountName(string $contraAccountName): BankStatementLine
    {
        $this->contraAccountName = $contraAccountName;
        return $this;
    }

    /**
     * @return string
     */
    public function getContraIban(): string
    {
        return $this->contraIban;
    }

    /**
     * @param string $contraIban
     * @return $this
     */
    public function setContraIban(string $contraIban): BankStatementLine
    {
        $this->contraIban = $contraIban;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string  {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): BankStatementLine
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndToEndId(): string  {
        return $this->endToEndId;
    }

    /**
     * @param string $endToEndId
     * @return $this
     */
    public function setEndToEndId(string $endToEndId ): BankStatementLine
    {
        $this->endToEndId = $endToEndId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentReference(): string  {
        return $this->paymentReference;
    }

    /**
     * @param string $paymentReference
     * @return $this
     */
    public function setPaymentReference(string $paymentReference ): BankStatementLine
    {
        $this->paymentReference = $paymentReference;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecognition(): ?string  {
        return $this->recognition;
    }

    /**
     * @param string|null $recognition
     * @return $this
     */
    public function setRecognition(?string $recognition ): BankStatementLine
    {
        $this->recognition = $recognition;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string  {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference ): BankStatementLine
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnReason(): string  {
        return $this->returnReason;
    }

    /**
     * @param string $returnReason
     * @return $this
     */
    public function setReturnReason(string $returnReason ): BankStatementLine
    {
        $this->returnReason = $returnReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getDimension1(): string  {
        return $this->dimension1;
    }

    /**
     * @param string $dimension1
     * @return $this
     */
    public function setDimension1(string $dimension1 ): BankStatementLine
    {
        $this->dimension1 = $dimension1;
        return $this;
    }

    /**
     * @return string
     */
    public function getDimension2(): string  {
        return $this->dimension2;
    }

    /**
     * @param string $dimension2
     * @return $this
     */
    public function setDimension2(string $dimension2 ): BankStatementLine
    {
        $this->dimension2 = $dimension2;
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
     * @param Currency $currency
     */
    public function setCurrency(Currency &$currency): BankStatementLine
    {
        $this->currency = $currency;
        return $this;
    }
}
