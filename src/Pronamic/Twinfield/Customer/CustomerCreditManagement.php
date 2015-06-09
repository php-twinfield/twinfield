<?php

namespace Pronamic\Twinfield\Customer;

class CustomerCreditManagement
{
    private $responsibleUser;       #string(16)         The credit manager.
    private $baseCreditLimit = 0;   #amount             The credit limit amount.
    private $sendReminder = true;   #true/email/false   Determines if and how a customer will be reminded.
    private $reminderEmail;         #string(200)        Mandatory if sendreminder is email.
    private $blocked = false;       #true/false         Are related projects for this cust. blocked in time & expenses.
    private $freeText1;             #true/false         Right of use.
    private $freeText2;             #string(40)         Segment code.
    private $freeText3;             #                   Not in use
    private $comment;               #string()           Comment.

    public function getResponsibleUser()
    {
        return $this->responsibleUser;
    }

    public function getBaseCreditLimit()
    {
        return $this->baseCreditLimit;
    }

    public function getSendReminder()
    {
        return $this->sendReminder;
    }

    public function getReminderEmail()
    {
        return $this->reminderEmail;
    }

    public function getBlocked()
    {
        return $this->blocked;
    }

    public function getFreeText1()
    {
        return $this->freeText1;
    }

    public function getFreeText2()
    {
        return $this->freeText2;
    }

    public function getFreeText3()
    {
        return $this->freeText3;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setResponsibleUser($responsibleUser)
    {
        $this->responsibleUser = $responsibleUser;
        return $this;
    }

    public function setBaseCreditLimit($baseCreditLimit)
    {
        $this->baseCreditLimit = $baseCreditLimit;
        return $this;
    }

    public function setSendReminder($sendReminder)
    {
        $this->sendReminder = $sendReminder;
        return $this;
    }

    public function setReminderEmail($reminderEmail)
    {
        $this->reminderEmail = $reminderEmail;
        return $this;
    }

    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }

    public function setFreeText1($freeText1)
    {
        $this->freeText1 = $freeText1;
        return $this;
    }

    public function setFreeText2($freeText2)
    {
        $this->freeText2 = $freeText2;
        return $this;
    }

    public function setFreeText3($freeText3)
    {
        $this->freeText3 = $freeText3;
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}
