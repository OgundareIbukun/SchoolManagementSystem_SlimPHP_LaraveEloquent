<?php

namespace Main\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;

class MatchesPasswordException extends ValidationException
{

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'The password is not VALID',
        ],
    ];
}
