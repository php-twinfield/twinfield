<?php

namespace Pronamic\Twinfield\Article;

class Article
{
    private $_code;
    private $_office;
    private $_status;
    private $_type;
    private $_name;
    private $_shortName;
    private $_unitNameSingular;
    private $_unitNamePlural;
    private $_vatCode;
    private $_allowChangeVatCode = 'false';
    private $_performanceType;
    private $_allowChangePerformanceType;
    private $_percentage;
    private $_allowDiscountorPremium = 'true';
    private $_allowChangeUnitsPrice = 'false';
    private $_allowDecimalQuantity = 'false';
    private $_lines = [];

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($_code)
    {
        $this->code = $_code;
        return $this;
    }

    public function getID()
    {
        trigger_error('getID is a deprecated function: Use getCode', E_USER_NOTICE);
        return $this->getCode();
    }

    public function setID($ID)
    {
        trigger_error('setID is a deprecated function: Use setCode', E_USER_NOTICE);
        return $this->setCode($ID);
    }

    public function getOffice()
    {
        return $this->office;
    }

    public function setOffice($_office)
    {
        $this->office = $_office;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($_status)
    {
        $this->status = $_status;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($_type)
    {
        $this->type = $_type;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($_name)
    {
        $this->name = $_name;
        return $this;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($_shortName)
    {
        $this->shortName = $_shortName;
        return $this;
    }

    public function getUnitNameSingular()
    {
        return $this->unitNameSingular;
    }

    public function setUnitNameSingular($_unitNameSingular)
    {
        $this->unitNameSingular = $_unitNameSingular;
        return $this;
    }

    public function getUnitNamePlural()
    {
        return $this->unitNamePlural;
    }

    public function setUnitNamePlural($_unitNamePlural)
    {
        $this->unitNamePlural = $_unitNamePlural;
        return $this;
    }

    public function getVatCode()
    {
        return $this->vatCode;
    }

    public function setVatCode($_vatCode)
    {
        $this->vatCode = $_vatCode;
        return $this;
    }

    public function getAllowChangeVatCode()
    {
        return $this->allowChangeVatCode;
    }

    public function setAllowChangeVatCode($_allowChangeVatCode)
    {
        $this->allowChangeVatCode = var_export($_allowChangeVatCode, true);
        return $this;
    }

    public function getPerformanceType()
    {
        return $this->performanceType;
    }

    public function setPerformanceType($_performanceType)
    {
        $this->performanceType = $_performanceType;
        return $this;
    }

    public function getAllowChangePerformanceType()
    {
        return $this->allowChangePerformanceType;
    }

    public function setAllowChangePerformanceType($_allowChangePerformanceType)
    {
        $this->allowChangePerformanceType
            = var_export($_allowChangePerformanceType, true);
        return $this;
    }

    public function getPercentage ()
    {
        return $this->percentage;
    }

    public function setPercentage($_percentage)
    {
        $this->percentage = $_percentage;
        return $this;
    }

    public function getAllowDiscountorPremium ()
    {
        return $this->allowDiscountorPremium;
    }

    public function setAllowDiscountorPremium($_allowDiscountorPremium)
    {
        $this->allowDiscountorPremium = var_export($_allowDiscountorPremium, true);
        return $this;
    }

    public function getAllowChangeUnitsPrice  ()
    {
        return $this->allowChangeUnitsPrice ;
    }

    public function setAllowChangeUnitsPrice($_allowChangeUnitsPrice)
    {
        $this->allowChangeUnitsPrice = var_export($_allowChangeUnitsPrice, true);
        return $this;
    }

    public function getAllowDecimalQuantity ()
    {
        return $this->allowDecimalQuantity;
    }

    public function setAllowDecimalQuantity($_allowDecimalQuantity)
    {
        $this->allowDecimalQuantity = var_export($_allowDecimalQuantity, true);
        return $this;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function addLine(ArticleLine $line)
    {
        $this->lines[] = $line;
        return $this;
    }
}
