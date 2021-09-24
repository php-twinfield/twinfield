<?php

declare(strict_types=1);

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use Webmozart\Assert\Assert;

final class VatCodeDetail
{
    use OfficeField;

    private $status;
    private $code;
    private $name;
    private $shortName;
    private $uid;
    private $created;
    private $modified;
    private $touched;
    private $type;
    private $user;
    private $percentages = [];

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
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

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getUID(): string
    {
        return $this->uid;
    }

    public function setUID(string $uid): self
    {
        $this->uid = $uid;
        return $this;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setCreatedFromString(string $dateStr): self
    {
        $this->created = Util::parseDateTime($dateStr);
        return $this;
    }

    public function getModified(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setModifiedFromString(string $dateStr): self
    {
        $this->modified = Util::parseDateTime($dateStr);
        return $this;
    }

    public function getTouched(): int
    {
        return $this->touched;
    }

    public function setTouched(int $touched): self
    {
        $this->touched = $touched;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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
     * @return VatCodePercentage[]
     */
    public function getPercentages(): array
    {
        return $this->percentages;
    }

    public function setPercentages(array $percentages): self
    {
        Assert::allIsInstanceOf($percentages, VatCodePercentage::class);

        $this->percentages = $percentages;
        return $this;
    }
}
