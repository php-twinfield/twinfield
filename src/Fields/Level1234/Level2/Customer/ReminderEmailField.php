<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

trait ReminderEmailField
{
    /**
     * Reminder email field
     * Used by: CustomerCreditManagement
     *
     * @var string|null
     */
    private $reminderEmail;

    /**
     * @return null|string
     */
    public function getReminderEmail(): ?string
    {
        return $this->reminderEmail;
    }

    /**
     * @param null|string $reminderEmail
     * @return $this
     */
    public function setReminderEmail(?string $reminderEmail): self
    {
        $this->reminderEmail = $reminderEmail;
        return $this;
    }
}