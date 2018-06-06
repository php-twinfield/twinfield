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
     * @var \DateTimeInterface|null
     */
    private $signatureDate;

    /**
     * Date on which the first run was collected.
     *
     * @var \DateTimeInterface|null
     */
    private $firstRunDate;

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return $this
     */
    public function setID(string $ID): self
    {
        Assert::maxLength($ID, 35);
        $this->ID = $ID;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getSignatureDate(): ?\DateTimeInterface
    {
        return $this->signatureDate;
    }

    /**
     * @param \DateTimeInterface|null $date
     * @return $this
     */
    public function setSignatureDate(?\DateTimeInterface $date): self
    {
        $this->signatureDate = $date;

        return $this;
    }

    /**
     * @param string|null $dateString
     * @return $this
     * @throws Exception
     */
    public function setSignatureDateFromString(?string $dateString): self
    {
        if (!empty($dateString)) {
            $dateString = Util::parseDate($dateString);
        }

        return $this->setSignatureDate($dateString);
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFirstRunDate(): ?\DateTimeInterface
    {
        return $this->firstRunDate;
    }

    /**
     * @param \DateTimeInterface|null $date
     * @return $this
     */
    public function setFirstRunDate(?\DateTimeInterface $date): self
    {
        $this->firstRunDate = $date;

        return $this;
    }

    /**
     * @param string|null $dateString
     * @return $this
     * @throws Exception
     */
    public function setFirstRunDateFromString(?string $dateString): self
    {
        if (!empty($dateString)) {
            $dateString = Util::parseDate($dateString);
        }

        return $this->setFirstRunDate($dateString);
    }
}
