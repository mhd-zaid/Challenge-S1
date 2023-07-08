<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EmailUserExist extends Constraint
{
    public string $message = 'The email {{ title }} already exist. Your are employee, please use your personnal email.';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}