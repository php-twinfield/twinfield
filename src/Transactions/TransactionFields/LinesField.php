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
     * @var TransactionLine[]
     */
    private $lines = [];

    /**
     * @return string The class name for transaction lines supported by this transaction. Must be an implementation of
     *                TransactionLine.
     */
    abstract public function getLineClassName(): string;

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
        $type_order = [LineType::TOTAL(), LineType::DETAIL(), LineType::VAT()];
        usort(
            $this->lines,
            function (TransactionLine $a, TransactionLine $b) use ($type_order): int
            {
                $type_index_a = array_search($a->getLineType(), $type_order, false);
                $type_index_b = array_search($b->getLineType(), $type_order, false);

                if ($type_index_a != $type_index_b) {
                    return $type_index_a <=> $type_index_b;
                }

                return $a->getId() <=> $b->getId();
            }
        );

        return $this->lines;
    }

    /**
     * @param TransactionLine $line
     * @return $this
     */
    public function addLine(TransactionLine $line)
    {
        Assert::isInstanceOf($line, $this->getLineClassName());

        $this->lines[] = $line;

        $line->setTransaction($this);

        return $this;
    }

    /**
     * @param TransactionLine[] $lines
     */
    public function setLines(array $lines): void
    {
        /*
         * Don't set $this->lines here directly. Some classes that use this trait overwrite the addLine() method.
         */
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }
}
