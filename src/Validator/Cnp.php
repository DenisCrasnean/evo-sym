<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Cnp extends Constraint
{
    public $message = 'The is not a valid CNP';
}
