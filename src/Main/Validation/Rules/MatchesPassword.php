<?php

namespace Main\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class MatchesPassword extends AbstractRule
{

    /**
     * @var string
     */
    protected $password;

    /**
     * MatchesPassword constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
        $this->pswd=$password;
    }

    public function validate($input)
    {
        return password_verify($input, $this->password);
    }
}