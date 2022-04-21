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
            $cnpForControl = str_split(substr($cnp, 0, 12));
            $controlConstant = str_split('279146358279');
            $cnpControlDigit = 0;

            foreach ($controlConstant as $key => $controlConstantDigit) {
                $cnpControlDigit += intval($controlConstantDigit) * intval($cnpForControl[$key]);
            }

            $cnpControlDigit = $cnpControlDigit % 11;

            if (10 === $cnpControlDigit) {
                $cnpControlDigit = 1;
            }

            if (intval(substr($cnp, 12, 1)) === $cnpControlDigit) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
