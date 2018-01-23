<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionLineFields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles
 * @todo Add documentation and typehints to all properties.
 */
class Article
{
    use VatCodeField;
    use OfficeField;

    private $code;
    private $status;
    private $type;
    private $name;
    private $shortName;
    private $unitNameSingular;
    private $unitNamePlural;
    private $allowChangeVatCode = false;
    private $performanceType;
    private $allowChangePerformanceType;
    private $percentage;
    private $allowDiscountorPremium = true;
    private $allowChangeUnitsPrice = false;
    private $allowDecimalQuantity = false;
    private $lines = [];

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getUnitNameSingular()
    {
        return $this->unitNameSingular;
    }

    public function setUnitNameSingular($unitNameSingular)
    {
        $this->unitNameSingular = $unitNameSingular;
        return $this;
    }

    public function getUnitNamePlural()
    {
        return $this->unitNamePlural;
    }

    public function setUnitNamePlural($unitNamePlural)
    {
        $this->unitNamePlural = $unitNamePlural;
        return $this;
    }

    public function getAllowChangeVatCode(): bool
    {
        return $this->allowChangeVatCode;
    }

    public function setAllowChangeVatCode(bool $allowChangeVatCode): self
    {
        $this->allowChangeVatCode = $allowChangeVatCode;
        return $this;
    }

    public function getPerformanceType()
    {
        return $this->performanceType;
    }

    public function setPerformanceType($performanceType)
    {
        $this->performanceType = $performanceType;
        return $this;
    }

    public function getAllowChangePerformanceType()
    {
        return $this->allowChangePerformanceType;
    }

    public function setAllowChangePerformanceType($allowChangePerformanceType)
    {
        $this->allowChangePerformanceType
            = $allowChangePerformanceType;
        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getAllowDiscountorPremium(): bool
    {
        return $this->allowDiscountorPremium;
    }

    public function setAllowDiscountorPremium(bool $allowDiscountorPremium): self
    {
        $this->allowDiscountorPremium = $allowDiscountorPremium;
        return $this;
    }

    public function getAllowChangeUnitsPrice(): bool
    {
        return $this->allowChangeUnitsPrice;
    }

    public function setAllowChangeUnitsPrice(bool $allowChangeUnitsPrice): self
    {
        $this->allowChangeUnitsPrice = $allowChangeUnitsPrice;
        return $this;
    }

    public function getAllowDecimalQuantity(): bool
    {
        return $this->allowDecimalQuantity;
    }

    public function setAllowDecimalQuantity(bool $allowDecimalQuantity): self
    {
        $this->allowDecimalQuantity = $allowDecimalQuantity;
        return $this;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function addLine(ArticleLine $line)
    {
        $this->lines[$line->getID()] = $line;
        return $this;
    }

    public function removeLine($index)
    {
        if (array_key_exists($index, $this->lines)) {
            unset($this->lines[$index]);
            return true;
        } else {
            return false;
        }
    }
}
