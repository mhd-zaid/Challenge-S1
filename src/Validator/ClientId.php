<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ClientId extends Constraint
{
    public string $message = 'The customer id {{ title }} does not exist or the token is invalid.';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}