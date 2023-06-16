<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PasswordMatch extends Constraint
{
    public string $message = 'The password does not match.';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}