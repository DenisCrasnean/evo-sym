<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Password) {
            throw new UnexpectedTypeException($constraint, Password::class);
        }

        $validateFormat = preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A -Z])(?=.*[a-zA-Z]).{8,}$/", $value);

        if (false !== $validateFormat) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
