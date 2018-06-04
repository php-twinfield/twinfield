<?php

namespace PhpTwinfield;

use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 */
class CustomerCollectMandate
{
    /**
     * Mandate id which the debtor can collect with.
     *
     * @var string
     */
    private $ID;

    /**
     * Date on which the mandate is signed.
     *
     * @var string
     */
    private $signatureDate;

    /**
     * Date on which the first run was collected.
     *
     * @var string|null
     */
    private $firstRunDate;

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        Assert::maxLength($ID, 35);
        $this->ID = $ID;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureDate(): string
    {
        return $this->signatureDate;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setSignatureDate(\DateTimeInterface $date): self
    {
        $this->signatureDate = Util::formatDate($date);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstRunDate(): ?string
    {
        return $this->firstRunDate;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setFirstRunDate(\DateTimeInterface $date): self
    {
        $this->firstRunDate = Util::formatDate($date);

        return $this;
    }
}
