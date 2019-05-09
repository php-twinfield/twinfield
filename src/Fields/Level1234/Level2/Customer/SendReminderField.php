<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

use PhpTwinfield\Enums\SendReminder;

trait SendReminderField
{
    /**
     * Send reminder field
     * Used by: CustomerCreditManagement
     *
     * @var SendReminder|null
     */
    private $sendReminder;

    public function getSendReminder(): ?SendReminder
    {
        return $this->sendReminder;
    }

    /**
     * @param SendReminder|null $sendReminder
     * @return $this
     */
    public function setSendReminder(?SendReminder $sendReminder): self
    {
        $this->sendReminder = $sendReminder;
        return $this;
    }

    /**
     * @param string|null $sendReminderString
     * @return $this
     * @throws Exception
     */
    public function setSendReminderFromString(?string $sendReminderString)
    {
        return $this->setSendReminder(new SendReminder((string)$sendReminderString));
    }
}