<?php

namespace App\Tests\Validator;

use App\Validator\Password;
use App\Validator\PasswordValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PasswordValidatorTest extends ConstraintValidatorTestCase
{
    public function testPasswordIsInvalid()
    {
        $password = "asdasd";
        $result = $this->validator->validate($password, new Password());

        $this->buildViolation("The is not a valid Password")->assertRaised();
    }

    public function testPasswordIsValid()
    {
        $password = "admin123asdasdasda";
        $result = $this->validator->validate($password, new Password());
        $this->assertNoViolation();
    }

    protected function createValidator() : PasswordValidator
    {
        return new PasswordValidator();
    }
}
