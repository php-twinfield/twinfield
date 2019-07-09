<?php

namespace PhpTwinfield\ApiConnectors;

final class ApiOptions
{
    /**
     * The key for the configuration array that indicates the max retires
     * @var string
     */
    public const CONFIG_MAX_RETRIES = 'max_retries';

    /**
     * The key for the configuration array inside the BaseApiConnector
     * @var string
     */
    public const CONFIG_EXCEPTION_MESSAGES = 'config_exception_messages';

    /**
     * The key for the configuration array that indicates the exceptions messages that would be retried
     * This key will overwrite the default retriable messages.
     * @var string
     */
    public const RETRIABLE_EXCEPTION_MESSAGES = 'retriable_messages';
    /**
     * The key for the configuration array that indicates the exceptions messages that would be retried
     * This key will append to the array of retriable messages.
     * @var string
     */
    public const APPEND_RETRIABLE_EXCEPTION_MESSAGES = 'append_retriable_messages';


}