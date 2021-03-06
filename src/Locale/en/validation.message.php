<?php

/**
 * define default error message for each filter.
 */

use WScore\Validator\Filters\ConfirmWith;
use WScore\Validator\Filters\ValidateMatch;
use WScore\Validator\Filters\Required;
use WScore\Validator\Filters\StringLength;
use WScore\Validator\Filters\ValidateDateTime;
use WScore\Validator\Filters\ValidateFloat;
use WScore\Validator\Filters\ValidateInteger;
use WScore\Validator\Filters\ValidateUtf8String;
use WScore\Validator\Locale\Messages;

return [
    // a fall back error message.
    Messages::class => "validation failed",

    // fail for invalid charset string.
    ValidateUtf8String::ERROR_INVALID_CHAR => 'The input is invalid UTF-8 character.',
    ValidateUtf8String::ERROR_ARRAY_INPUT => 'The input is an array. ',
    ValidateInteger::class => 'The input is not a valid integer. ',
    ValidateFloat::class => 'The input is not a valid float. ',
    ValidateDateTime::class => 'Invalid DateTime input value.',

    // error messages for StringLength.
    StringLength::LENGTH => "The input must be {length} characters.",
    StringLength::MAX => "The input is more than {max} characters.",
    StringLength::MIN => "The input is less than {min} characters.",

    // error messages for Match
    ValidateMatch::IP => 'The input is not a valid IP address.',
    ValidateMatch::EMAIL => 'The input is not a valid email address',
    ValidateMatch::URL => 'The input is not a valid URL',
    ValidateMatch::MAC => 'The input is not a valid MAC address',

    // required value.
    Required::class => "The input field is required.",

    // ConfirmWith
    ConfirmWith::ERROR_MISSING => 'The field for confirmation is empty.',
    ConfirmWith::ERROR_DIFFER => 'The input differs from confirmation.',
];

/*
 * NOTE:
 *
 * The default error messages do not contain {name} as default.
 * When validating a single value, name may not present.
 * Because of this, the default error messages doe not contain {name}
 * as part of the messages.
 */