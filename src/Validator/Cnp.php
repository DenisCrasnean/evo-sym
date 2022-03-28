<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Cnp extends Constraint
{
    public string $message = 'The is not a valid CNP';
}
