<?php

namespace App\Validator;

use App\Controller\Filter\ProgrammeFilter;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class ProgrammeFilterValidator extends ConstraintValidator
{
    /**
     * @Assert\Collection
     */
    private ProgrammeFilter $programmeFilter;

    public function __construct(ProgrammeFilter $programmeFilter)
    {
        $this->programmeFilter = $programmeFilter;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof \App\Validator\ProgrammeFilter) {
            throw new UnexpectedTypeException($constraint, ProgrammeFilter::class);
        }

        $validator = Validation::createValidator();
        $params = $value;
        $constraints = null;

        foreach ($params as $key => $param) {
            if (in_array($param, $this->programmeFilter->availableFilters)) {
                $constraints = $this->applyConstraints($params);

                return;
            }
        }

        $validator->validate($value, $constraints);

//        $this->context->buildViolation($constraint->message)
//            ->setParameter('{{ string }}', $value)
//            ->addViolation();
    }

    private function applyConstraints(array $params): Assert\Collection
    {
        $constraint = null;
        $constraints = [];

        foreach ($params as $key => $value) {
            $valueType = $this->programmeFilter->availableFilters[$key][$value]['type'];

            if ($valueType === 'integer') {
                $constraints[] = $this->filterByInteger($value);
            }

            if ($valueType === 'string') {
                $constraints[] = $this->filterByString($value);
            }

            if ($valueType === 'datetime') {
                $constraints[] = $this->filterByDate($value);
            }
//
//            if (isset($params['startTime']) && isset($params['endDate'])) {
//                $constraints[] = $this->filterBetweenDate($params['startTime'], $params['endDate']);
//            }
//
            if ($valueType === 'boolean') {
                $constraints[] = $this->filterByBoolean($value);
            }

            $constraint = new Assert\Collection($constraints);
        }

        return $constraint;
    }

    public function filterByInteger(int $value): array
    {
        return [
            $value => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('integer'),
                new Assert\Positive(),
            ]),
        ];
    }

    public function filterByString($value): array
    {
        return [
            $value => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(['min' => 4])
            ]),
        ];
    }

    public function filterByDate($value): array
    {
        return [
            $value => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\DateTimeValidator(),
            ]),
        ];
    }

    public function filterBetweenDate(string $startTime, string $endTime): array
    {
        return [
            new Assert\Required([
                new Assert\NotBlank(),
                new Assert\DateTimeValidator(),
                new Assert\LessThan($endTime)
            ]),

            new Assert\Required([
                new Assert\NotBlank(),
                new Assert\DateTimeValidator(),
                new Assert\GreaterThan($startTime),
            ]),
        ];
    }

    public function filterByBoolean(string $value): array
    {
        if (strtolower($value) === 'da' || $value == true) {
            $value = 1;
        }

        if (strtolower($value) == 'nu' || $value == false) {
            $value = 0;
        }

        return [
            $value => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('bool')
            ]),
        ];
    }
}
