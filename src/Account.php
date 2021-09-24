<?php

declare(strict_types=1);

namespace PhpTwinfield;

use PhpTwinfield\Enums\LineType;

final class Account
{
    private $id;
    private $dim1;
    private $groupCountry;
    private $group;
    private $percentage;
    private $lineType;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDim1(): string
    {
        return $this->dim1;
    }

    public function setDim1(string $dim1): self
    {
        $this->dim1 = $dim1;
        return $this;
    }

    public function getGroupCountry(): string
    {
        return $this->groupCountry;
    }

    public function setGroupCountry(string $groupCountry): self
    {
        $this->groupCountry = $groupCountry;
        return $this;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setGroup(string $group): self
    {
        $this->group = $group;
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

    public function getLineType(): LineType
    {
        return $this->lineType;
    }

    public function setLineType(LineType $lineType): self
    {
        $this->lineType = $lineType;
        return $this;
    }
}
