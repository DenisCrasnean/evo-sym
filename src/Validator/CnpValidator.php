<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CnpValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Cnp) {
            throw new UnexpectedTypeException($constraint, Cnp::class);
        }

        $cnp = preg_replace("/\s+/", '', $value);

        if (13 === strlen($cnp)) {
            preg_match('/^([1-8]{1})([1-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-5]{1}[0-9]{1})([0-9]{3})([1-9]{1})$/', $cnp, $matches);
            $cnpForControl = str_split(substr($matches[0], 0, 12), 1);
            $controlConstant = str_split('279146358279', 1);
            $cnpControlDigit = 0;

            foreach ($controlConstant as $key => $controlConstantDigit) {
                $cnpControlDigit += intval($controlConstantDigit) * intval($cnpForControl[$key]);
            }

            $cnpControlDigit = $cnpControlDigit % 11;

            if (10 === $cnpControlDigit) {
                $cnpControlDigit = 1;
            }

            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
