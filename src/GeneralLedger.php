<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * Class GeneralLedger
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class GeneralLedger extends BaseObject
{
    use OfficeField;

    private $beginPeriod;
    private $beginYear;
    private $behaviour;
    private $code;
    private $endPeriod;
    private $endYear;
    private $group;
    private $inUse;
    private $name;
    private $shortName;
    private $status;
    private $touched;
    private $type;
    private $uid;
    private $financials;

    public function getBeginPeriod()
    {
        return $this->beginPeriod;
    }

    public function setBeginPeriod($beginPeriod)
    {
        $this->beginPeriod = $beginPeriod;
    }

    public function getBeginYear()
    {
        return $this->beginYear;
    }

    public function setBeginYear($beginYear)
    {
        $this->beginYear = $beginYear;
    }

    public function getBehaviour()
    {
        return $this->behaviour;
    }

    public function setBehaviour($behaviour)
    {
        $this->behaviour = $behaviour;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

     public function getEndPeriod()
    {
        return $this->endPeriod;
    }

    public function setEndPeriod($endPeriod)
    {
        $this->endPeriod = $endPeriod;
    }

    public function getEndYear()
    {
        return $this->endYear;
    }

    public function setEndYear($endYear)
    {
        $this->endYear = $endYear;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getInUse()
    {
        return $this->inUse;
    }

    public function setInUse($inUse)
    {
        $this->inUse = $inUse;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
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

    public function getTouched()
    {
        return $this->touched;
    }

    public function setTouched($touched)
    {
        $this->touched = $touched;
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

    public function getUID()
    {
        return $this->uid;
    }

    public function setUID($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    public function getFinancials(): GeneralLedgerFinancials
    {
        return $this->financials;
    }

    public function setFinancials(GeneralLedgerFinancials $financials)
    {
        $this->financials = $financials;
        return $this;
    }
}
