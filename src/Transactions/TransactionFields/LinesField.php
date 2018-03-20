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
    private $lines_per_type = [];

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
        if (\array_key_exists($line_type->getValue(), $this->lines_per_type)) {
            return count($this->lines_per_type[$line_type->getValue()]);
        }

        return 0;
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
        $lines = [];

        foreach ($this->getTotalLines() as $line) {
            $lines[] = $line;
        }

        foreach ($this->getDetailLines() as $line) {
            $lines[] = $line;
        }

        foreach ($this->getVatLines() as $line) {
            $lines[] = $line;
        }

        return $lines;
    }

    private function getTotalLines(): array
    {
        return $this->getLinesOfType(LineType::TOTAL());
    }

    private function getLinesOfType(LineType $line_type): array
    {
        if (\array_key_exists($line_type->getValue(), $this->lines_per_type)) {
            return $this->lines_per_type[$line_type->getValue()];
        }

        return [];
    }

    private function getDetailLines(): array
    {
        return $this->getLinesOfType(LineType::DETAIL());
    }

    private function getVatLines(): array
    {
        return $this->getLinesOfType(LineType::VAT());
    }

    /**
     * @param TransactionLine $line
     * @return $this
     */
    public function addLine(TransactionLine $line)
    {
        Assert::isInstanceOf($line, $this->getLineClassName());

        $this->addLinePerType($line);
        $line->setTransaction($this);

        return $this;
    }

    private function addLinePerType(TransactionLine $line)
    {
        $line_type = $line->getLineType()->getValue();

        if (!\array_key_exists($line_type, $this->lines_per_type)) {
            $this->lines_per_type[$line_type] = [];
        }

        $this->lines_per_type[$line_type][] = $line;
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
