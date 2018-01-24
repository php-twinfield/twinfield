<?php

namespace PhpTwinfield;

class Exception extends \Exception
{
    public static function invalidTransactionClassName(string $transactionClassName): self
    {
        return new self(sprintf(
            "Specified class name %s is not a subclass of BaseTransaction.",
            $transactionClassName
        ));
    }

    public static function transactionLineDoesNotExist(string $transactionLineId): self
    {
        return new self(sprintf(
            "Specified transaction line with ID '%s' doesn't exist.",
            $transactionLineId
        ));
    }

    public static function invalidLineTypeForTransaction(string $lineType, BaseTransactionLine $transactionLine): self
    {
        return new self(sprintf(
            "Invalid line type '%s' for transaction line class %s.",
            $lineType,
            get_class($transactionLine)
        ));
    }

    public static function invalidDimensionForLineType(int $dimensionNumber, BaseTransactionLine $transactionLine): self
    {
        return new self(sprintf(
            "Dimension %d is invalid for line class %s and type '%s'.",
            $dimensionNumber,
            get_class($transactionLine),
            $transactionLine->getLineType()
        ));
    }

    public static function invalidFieldForLineType(string $fieldName, BaseTransactionLine $transactionLine): self
    {
        return new self(sprintf(
            "Invalid field '%s' for line class %s and type '%s'.",
            $fieldName,
            get_class($transactionLine),
            $transactionLine->getLineType()
        ));
    }

    public static function invalidMatchStatusForLineType(
        string $matchStatus,
        BaseTransactionLine $transactionLine
    ): self {
        return new self(sprintf(
            "Invalid match status '%s' for line class %s and type '%s'.",
            $matchStatus,
            get_class($transactionLine),
            $transactionLine->getLineType()
        ));
    }
}
