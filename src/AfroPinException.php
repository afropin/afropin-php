<?php

namespace AfroPin;

/**
 * A request the API refused, or one that couldn't be made. Branch on $errorCode (for example pin_balance_low or
 * duplicate_external_reference); the message is for people and may change. $fields has per-field messages for input
 * problems. $status is the HTTP status, or 0 when the API wasn't reached.
 */
class AfroPinException extends \RuntimeException
{
    public function __construct(public string $errorCode, string $message, public ?array $fields = null, public int $status = 0)
    {
        parent::__construct($message, $status);
    }
}
