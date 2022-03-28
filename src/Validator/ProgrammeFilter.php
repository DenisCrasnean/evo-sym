<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeFilter extends Constraint
{
    public string $message = 'The query provided is not valid.';
}
