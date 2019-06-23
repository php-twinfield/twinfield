<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CommentField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\BaseCreditLimitField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\BlockedField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\BlockedLockedField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\BlockedModifiedField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\FreeText1Field;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\ReminderEmailField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\ResponsibleUserField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\SendReminderField;
use PhpTwinfield\Fields\FreeText2Field;
use PhpTwinfield\Fields\FreeText3Field;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerCreditManagement extends BaseObject
{
    use BaseCreditLimitField;
    use BlockedField;
    use BlockedLockedField;
    use BlockedModifiedField;
    use CommentField;
    use FreeText1Field;
    use FreeText2Field;
    use FreeText3Field;
    use ReminderEmailField;
    use ResponsibleUserField;
    use SendReminderField;

    public function __construct()
    {
        $this->setSendReminder(\PhpTwinfield\Enums\SendReminder::TRUE());
    }
}
