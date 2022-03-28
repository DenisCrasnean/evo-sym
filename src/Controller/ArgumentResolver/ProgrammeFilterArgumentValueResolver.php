<?php

namespace App\Controller\ArgumentResolver;

use App\Controller\Filter\ProgrammeFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ProgrammeFilterArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return ProgrammeFilter::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $programmeFilter = new ProgrammeFilter();

        yield $programmeFilter;
    }
}