<?php

namespace PhpTwinfield;

/**
 * Class VatCode
 *
 * @author Emile Bons <emile@emilebons.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatCode extends BaseObject
{

    private $code;
    private $created;
    private $modified;
    private $name;
    private $shortName;
    private $status;
    private $touched;
    private $type;
    private $uid;
    private $user;
    private $percentages = [];

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
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
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPercentages()
    {
        return $this->percentages;
    }

    public function addPercentage(VatCodePercentage $percentage)
    {
        $this->percentages[] = $percentage;
        return $this;
    }

    public function removePercentage($index)
    {
        if (array_key_exists($index, $this->percentages)) {
            unset($this->percentages[$index]);
            return true;
        } else {
            return false;
        }
    }
}
