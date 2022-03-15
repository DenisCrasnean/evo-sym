<?php

namespace App\Tests\Validator;

use App\Validator\Cnp;
use App\Validator\CnpValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CnpValidatorTest extends ConstraintValidatorTestCase
{
    public function testCnpIsNotValid()
    {
        $cnp = "154656";
        $result = $this->validator->validate($cnp, new Cnp());

        $this->buildViolation("The is not a valid CNP")->assertRaised();
    }

    public function testCnpIsValid()
    {
        $cnp = "1941020303927";
        $result = $this->validator->validate($cnp, new Cnp());
        $this->assertNoViolation();
    }

    protected function createValidator() : CnpValidator
    {
        return new CnpValidator();
    }
}