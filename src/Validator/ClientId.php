<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ClientId extends Constraint
{
    public string $message = 'The client id {{ title }} does not exist.';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}