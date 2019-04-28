<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * Class Project
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class Project extends BaseObject
{
    use OfficeField;

    private $behaviour;
    private $code;
    private $inUse;
    private $name;
    private $shortName;
    private $status;
    private $touched;
    private $type;
    private $uid;
    private $vatCode;
    private $projects;

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

    public function getVatCode()
    {
        return $this->vatCode;
    }

    public function setVatCode($vatCode)
    {
        $this->vatCode = $vatCode;
        return $this;
    }

    public function getProjects(): ProjectProjects
    {
        return $this->projects;
    }

    public function setProjects(ProjectProjects $projects)
    {
        $this->projects = $projects;
        return $this;
    }
}
