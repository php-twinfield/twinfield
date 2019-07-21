<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

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
}
