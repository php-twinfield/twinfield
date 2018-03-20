<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLine;
use Webmozart\Assert\Assert;

/**
 * @package PhpTwinfield\Transactions\TransactionLineFields
 */
trait LinesField
{
    /**
     * @var TransactionLine[][]
     */
    private $lines_per_type = [
        LineType::TOTAL  => [],
        LineType::DETAIL => [],
        LineType::VAT    => [],
    ];

    /**
     * @return string The class name for transaction lines supported by this transaction. Must be an implementation of
     *                TransactionLine.
     */
    abstract public function getLineClassName(): string;

    protected function getLineCount(): int
    {
        return $this->getLineCountForType(LineType::TOTAL())
            + $this->getLineCountForType(LineType::DETAIL())
            + $this->getLineCountForType(LineType::VAT());
    }

    private function getLineCountForType(LineType $line_type): int
    {
        return count($this->lines_per_type[$line_type->getValue()]);
    }

    /**
     * @return TransactionLine[]
     */
    public function getLines(): array
    {
        /*
         * When creating the XML that is send to Twinfield, the lines should always be put in the order: one total line,
         * one or more detail lines and optionally one or more vat lines. Twinfield returns an error when the lines are
         * in an incorrect order.
         */
        return \array_merge(
            $this->getTotalLines(),
            $this->getDetailLines(),
            $this->getVatLines()
        );
    }

    private function getTotalLines(): array
    {
        return $this->lines_per_type[LineType::TOTAL()->getValue()];
    }

    private function getDetailLines(): array
    {
        return $this->lines_per_type[LineType::DETAIL()->getValue()];
    }

    private function getVatLines(): array
    {
        return $this->lines_per_type[LineType::VAT()->getValue()];
    }

    /**
     * @param TransactionLine $line
     * @return $this
     */
    public function addLine(TransactionLine $line)
    {
        Assert::isInstanceOf($line, $this->getLineClassName());

        if ($line->getLineType()->equals(LineType::TOTAL())) {
            Assert::eq($this->getLineCountForType(LineType::TOTAL()), 0, 'Cannot set a second TOTAL line');
        }

        $this->lines_per_type[$line->getLineType()->getValue()][] = $line;
        $line->setTransaction($this);

        return $this;
    }

    /**
     * @param TransactionLine[] $lines
     */
    public function setLines(array $lines): void
    {
        /*
         * Don't set the lines directly. Some classes that use this trait overwrite the addLine() method.
         */
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }
}
