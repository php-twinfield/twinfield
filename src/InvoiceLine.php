<?php
namespace PhpTwinfield;

use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionLineFields\VatCodeField;

/**
 * @see https://accounting.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceLine
{
    use FreeTextFields;
    use VatCodeField;

    private $ID;
    private $quantity;
    private $article;
    private $subArticle;
    private $description;
    private $unitsPriceExcl;
    private $unitsPriceInc;
    private $units;
    private $allowDiscountOrPremium;
    private $valueExcl;
    private $vatValue;
    private $valueInc;
    private $performanceDate;
    private $dim1;

    /**
     * @var PerformanceType|null Mandatory in case of an ICT VAT code.
     */
    private $performanceType;

    public function __construct($quantity = null, $article = null, $freeText1 = null, $freeText2 = null)
    {
        $this->ID = uniqid();

        $this->quantity  = $quantity;
        $this->article   = $article;
        $this->setFreeText1($freeText1);
        $this->setFreeText2($freeText2);
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    public function getSubArticle()
    {
        return $this->subArticle;
    }

    public function setSubArticle($subArticle)
    {
        $this->subArticle = $subArticle;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getUnitsPriceExcl()
    {
        return $this->unitsPriceExcl;
    }

    public function setUnitsPriceExcl($unitsPriceExcl)
    {
        $this->unitsPriceExcl = $unitsPriceExcl;
        return $this;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function setUnits($units)
    {
        $this->units = $units;
        return $this;
    }

    public function getAllowDiscountOrPremium()
    {
        return $this->allowDiscountOrPremium;
    }

    public function setAllowDiscountOrPremium($allowDiscountOrPremium)
    {
        $this->allowDiscountOrPremium = $allowDiscountOrPremium;
        return $this;
    }

    public function getValueExcl()
    {
        return $this->valueExcl;
    }

    public function setValueExcl($valueExcl)
    {
        $this->valueExcl = $valueExcl;
        return $this;
    }

    public function getVatValue()
    {
        return $this->vatValue;
    }

    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;
        return $this;
    }

    public function getValueInc()
    {
        return $this->valueInc;
    }

    public function setValueInc($valueInc)
    {
        $this->valueInc = $valueInc;
        return $this;
    }

    public function getUnitsPriceInc()
    {
        return $this->unitsPriceInc;
    }

    public function setUnitsPriceInc($unitsPriceInc)
    {
        $this->unitsPriceInc = $unitsPriceInc;
        return $this;
    }

    public function getPerformanceDate()
    {
        return $this->performanceDate;
    }

    public function setPerformanceDate($performanceDate)
    {
        $this->performanceDate = $performanceDate;
        return $this;
    }

    public function getPerformanceType(): ?PerformanceType
    {
        return $this->performanceType;
    }

    public function setPerformanceType(?PerformanceType $performanceType): self
    {
        $this->performanceType = $performanceType;
        return $this;
    }

    public function getDim1()
    {
        return $this->dim1;
    }

    public function setDim1($dim1)
    {
        $this->dim1 = $dim1;
        return $this;
    }
}
