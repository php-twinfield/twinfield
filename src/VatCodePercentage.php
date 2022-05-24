<?php

declare(strict_types=1);

namespace PhpTwinfield;

use Webmozart\Assert\Assert;

final class VatCodePercentage
{
    private $status;
    private $inUse;
    private $date;
    private $percentage;
    private $created;
    private $name;
    private $shortname;
    private $user;
    private $accounts = [];

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isInUse(): bool
    {
        return $this->inUse;
    }

    public function setInUse(bool $inUse): self
    {
        $this->inUse = $inUse;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDateFromString(string $dateStr): self
    {
        $this->date = Util::parseDate($dateStr);
        return $this;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setCreatedFromString(string $dateTimeStr): self
    {
        $this->created = Util::parseDateTime($dateTimeStr);
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getShortname(): string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;
        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function setAccounts(array $accounts): self
    {
        Assert::allIsInstanceOf($accounts, Account::class);

        $this->accounts = $accounts;
        return $this;
    }
}
