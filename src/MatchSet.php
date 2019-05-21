<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\MatchCode;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\Transaction\TransactionLine\MatchDateField;
use Webmozart\Assert\Assert;

class MatchSet
{
    use MatchDateField;
    use OfficeField;

    /**
     * @var Enums\MatchCode
     */
    private $matchCode;

    /**
     * @var MatchLine[]
     */
    private $lines = [];

    /**
     * @return MatchCode
     */
    public function getMatchCode(): MatchCode
    {
        return $this->matchCode;
    }

    /**
     * @param MatchCode $matchCode
     * @return $this
     */
    public function setMatchCode(MatchCode $matchCode)
    {
        $this->matchCode = $matchCode;

        return $this;
    }

    /**
     * @param MatchLine $line
     * @internal  Don't call this, use \PhpTwinfield\MatchLine::addToMatchSet
     * @see \PhpTwinfield\MatchLine::addToMatchSet()
     * @return $this
     */
    public function addLine(MatchLine $line): self
    {
        Assert::false(in_array($line, $this->lines));

        $this->lines[] = $line;

        return $this;
    }

    /**
     * @return MatchLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }
}
