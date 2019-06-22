<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;

class BrowseFieldOption extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
}
