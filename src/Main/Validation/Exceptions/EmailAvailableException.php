<?php

namespace Main\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;

class EmailAvailableException extends ValidationException
{
    
    public static $defaultTemplates = [
        self::MODE_DEFAULT  => [
            self::STANDARD => 'Eamil already exists On our System.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'Email does not exist have record on our System',
        ],
    ];
}
