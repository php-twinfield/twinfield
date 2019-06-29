<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Exception;

/**
 * Date field
 * Used by: BaseTransaction, ElectronicBankStatement, VatCodePercentage
 *
 * @package PhpTwinfield\Traits
 */
trait DateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $date;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface|null $date
     * @return $this
     */
    public function setDate(?\DateTimeInterface $date)
    {
        $this->date = $date;
        return $this;
    }
}
